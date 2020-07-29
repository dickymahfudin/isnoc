<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SlaPrtg;
use Carbon\Carbon;
use App\Models\NojsUser;

class SlaPrtgController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $nojs = $request->nojs;
        $end = Carbon::now()->format('Y-m-d');
        $start = (new Carbon($end))->format('Y-m-01');

        if ($nojs) {
            $datas = SlaPrtg::where('nojs', $nojs)
                ->whereBetween('time_local', [$start,  $end])
                ->orderBy('time_local', 'desc')
                ->get();

            $monthly = [];
            foreach ($datas as $value) {
                array_push($monthly, $value->lvd1_vsat);
            }

            $data = [
                "daily" => round(($datas[0]->lvd1_vsat), 1),
                "monthly" => round(array_sum($monthly) / count($monthly), 1),
                "log" => $datas
            ];
        } else {
            $data = "Please Select Nojs";
        }
        return response($data, 200);
    }

    public static function monthly()
    {
        $users = NojsUser::orderBy('site', 'asc')->get();

        // $sdate = "2020-07-09-00-00";
        // $edate = "2020-07-14-00-00";

        $edate = Carbon::now()->format('Y-m-d-00-00');
        $sdate = (new Carbon($edate))->subMonths(1)->format('Y-m-d-00-00');

        $username = "Power APT";
        $password = "APT12345";
        $start = (new Carbon($sdate))->format('Y-m-d H:i:s');
        $end = (new Carbon($edate))->format('Y-m-d H:i:s');
        $slaPrtg = [];
        $sla2 = [];
        $loop = [];

        foreach ($users as $key => $data) {
            $id = $data->id_lvdvsat;
            $new_data = [
                "id" => $id,
                "sdate" => $sdate,
                "edate" => $edate,
                "username" => $username,
                "password" => $password
            ];
            $temp = PrtgController::getPrtg($new_data);
            $sla = $temp->original;
            $uptimepercent = $sla["uptimepercent"];
            $uptime = $sla["uptime"];
            $downtime = $sla["downtime"];
            array_push($slaPrtg, [
                "nojs" => $data->nojs,
                "site" => $data->site,
                "lc" => $data->lc,
                "lvd1_vsat" => $uptimepercent,
                "uptime" => $uptime,
                "downtime" => $downtime,
            ]);
            if ($uptimepercent <= 95) {
                $dataSla2 = NojsLoggersController::sla2([
                    "nojs" => $data->nojs,
                    "sdate" => $start,
                    "edate" => $end
                ]);
                array_push($sla2, [
                    "detail" => [
                        "nojs" => $data->nojs,
                        "site" => $data->site,
                        "lc" => $data->lc,
                        "sla" => $uptimepercent
                    ],
                    "data" => $dataSla2["daily"]
                ]);

                array_push($loop, $key + 1);
            }
        }
        return response([
            "detail" => [
                "start" => $start,
                "end" => $end,
                "up" => count($slaPrtg) - count($sla2),
                "down" => count($sla2),
                "site_loop" => $loop
            ],
            "sla_prtg" => $slaPrtg,
            "sla_2" => $sla2
        ], 200);
    }
}