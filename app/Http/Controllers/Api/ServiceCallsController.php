<?php

namespace App\Http\Controllers\Api;

use App\models\ServiceCall;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Api\NojsLoggersController;
use Spatie\Backup\Helpers\Format;

class ServiceCallsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $nojs = $request->nojs;
        $status = $request->status;
        if (!$nojs && $status) {
            // $service = DB::table( 'service_calls')->where('status', $status )->get();
            $service = DB::table('service_calls')
                ->join('nojs_users', 'service_calls.nojs', '=', 'nojs_users.nojs')
                ->select('service_calls.*', 'nojs_users.site', 'nojs_users.lc', 'nojs_users.mitra', 'nojs_users.id_lvdvsat')
                ->where('status', 'like', '%' . $request->status . '%')
                ->get();
        } elseif ($nojs && $status) {
            $service = ServiceCall::where('nojs', $nojs)
                ->where('status', $status)->get();
        } else {
            $service = ServiceCall::all();
        }
        return response($service, 200);
    }

    public function withSlaLocal()
    {
        $service = DB::table('service_calls')
            ->join('nojs_users', 'service_calls.nojs', '=', 'nojs_users.nojs')
            ->select('service_calls.*', 'nojs_users.site', 'nojs_users.lc', 'nojs_users.mitra', 'nojs_users.id_lvdvsat')
            ->where('status', 'like', '%OPEN%')
            ->get();
        $data = [];
        foreach ($service as  $value) {
            $now = Carbon::now();
            $time = new Carbon($value->open_time);

            $msec = ($now->diffInMilliseconds($time));
            $day = floor($msec / 1000 / 60 / 60 / 24);
            $msec -= $day * 1000 * 60 * 60 * 24;
            $hh = floor($msec / 1000 / 60 / 60);
            $msec -= $hh * 1000 * 60 * 60;
            $mm = floor($msec / 1000 / 60);
            $msec -= $mm * 1000 * 60;
            $ss =  floor($msec / 1000);
            $msec -= $ss * 1000;

            $openTime = ($day != 0) ? "$day day" : (($hh != 0) ? "$hh Hours" : "$mm Minutes");

            $result =  SlaPrtgController::getSla($value->nojs);
            array_push($data, [
                "service_id" => $value->service_id,
                "nojs" => $value->nojs,
                "site" => $value->site,
                "pms " => $value->pms_state,
                "open_time" => $openTime,
                "lc" => $value->lc,
                "mitra" => $value->mitra,
                "error" => $value->error,
                "status" => "OPEN",
                "sla_prtg_day" => $result["daily"],
                "sla_prtg_month" => $result["monthly"],
            ]);
        }
        return response($data, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'service_id' => 'required|unique:service_calls,service_id',
            'nojs' => 'required',
            'open_time' => 'required',
        ]);
        $dataService = ServiceCall::create($request->all());
        return response($dataService, 201);
    }

    public function update(Request $request, ServiceCall $serviceCall)
    {
        // $this->validate($request, [
        //     'closed_time' => 'required',
        //     'status' => 'required',
        // ]);
        $serviceCall->update($request->all());
        return response($serviceCall, 200);
    }

    public static function ceckService($data)
    {
        $nojs = $data->nojs;
        $valueError = null;
        $error = 0;

        // if ($data->eh1 === $valueError) $error = 1;
        // if ($data->eh2 === $valueError) $error = 2;
        // if ($data->batt_volt1 === $valueError) $error = 3;
        // if ($data->edl1 === $valueError) $error = 4;
        // if ($data->edl2 === $valueError) $error = 5;

        $cek = ServiceCall::where('nojs', $nojs)
            ->where('status', 'OPEN')
            ->get();
        $pms = NojsLoggersController::pmsConvert($data->pms_state);
        if (($data->eh1 === $valueError) && ($data->eh2 === $valueError) && ($data->batt_volt1 === $valueError) && ($data->edl1 === $valueError) && ($data->edl2 === $valueError) || $pms < 16) {
            $error = (16 - $pms) * 3;
            $open_time = Carbon::now();
            $service = ServiceCall::orderBy('created_at', 'desc')
                ->orderBy('service_id', 'desc')
                ->first();
            if (count($cek) === 0 && $service !== null) {
                $service_id =  $service->service_id;
                $new_service_id = substr($service_id, 2 - (strlen($service_id))) + 1;

                $new_data = ([
                    'service_id' => 'SC' . $new_service_id,
                    'nojs' => $nojs,
                    'open_time' => $open_time,
                    'error' => $error,
                    'status' => 'OPEN',
                    'pms_state' => $pms
                ]);
            } elseif (count($cek) === 1) {
                $data = $cek[0];
                if ($data->error < 50) {
                    $new_data = ([
                        'pms_state' => $pms,
                        'error' => $error
                    ]);
                    ServiceCall::where('service_id', $cek[0]->service_id)
                        ->update($new_data);
                }
            } elseif ($service === null) {
                $new_data = ([
                    'service_id' => 'SC0',
                    'nojs' => $nojs,
                    'open_time' => $open_time,
                    'error' => $error,
                    'status' => 'OPEN',
                    'pms_state' => $pms
                ]);
            }
            try {
                ServiceCall::create($new_data);
            } catch (\Throwable $th) {
                //throw $
            }
        } else {
            if (count($cek) !== 0 && $pms == 16) {
                $id = $cek[0]->service_id;
                $closed_time = Carbon::now();
                $new_data = ([
                    'closed_time' => $closed_time,
                    'status' => 'CLOSED'
                ]);
                ServiceCall::where('service_id', $id)
                    ->update($new_data);
            }
        }
    }

    public function edit(ServiceCall $serviceCall)
    {
        return view('servicecalls.edit', [
            'serviceCall' => $serviceCall
        ]);
    }
}