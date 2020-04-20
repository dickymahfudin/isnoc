<?php

namespace App\Http\Controllers\Api;

use App\models\ServiceCall;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                ->select('service_calls.*', 'nojs_users.site', 'nojs_users.lc')
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
        $this->validate($request, [
            'closed_time' => 'required',
            'status' => 'required',
        ]);
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

        if (($data->eh1 === $valueError) && ($data->eh2 === $valueError) && ($data->batt_volt1 === $valueError) && ($data->edl1 === $valueError) && ($data->edl2 === $valueError)) {
            $error = ' ';
            $open_time = Carbon::now();
            $service = ServiceCall::orderBy('created_at', 'desc')
                ->orderBy('service_id', 'desc')
                ->first();
            if (count($cek) === 0 && $service !== null) {
                $service_id =  $service->service_id;
                $new_service_id = substr($service_id, 3 - (strlen($service_id))) + 1;

                $new_data = ([
                    'service_id' => '#SC' . $new_service_id,
                    'nojs' => $nojs,
                    'open_time' => $open_time,
                    'error' => $error,
                    'status' => 'OPEN'
                ]);
                ServiceCall::create($new_data);
            } elseif ($service === null) {
                $new_data = ([
                    'service_id' => '#SC1',
                    'nojs' => $nojs,
                    'open_time' => $open_time,
                    'error' => $error,
                    'status' => 'OPEN'
                ]);
                ServiceCall::create($new_data);
            }
        } else {
            if (count($cek) !== 0) {
                $id = $cek[0]->service_id;
                $closed_time = Carbon::now();
                $new_data = ([
                    'closed_time' => $closed_time,
                    'status' => 'CLOSED'
                ]);
                ServiceCall::where('service_id', $id)
                    ->update($new_data);
                echo 'closed';
            }
        }
    }
}