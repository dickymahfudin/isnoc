<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\NojsLoggersController;
use Illuminate\Support\Carbon;
use App\Exports\ReportSlaMultipleSheet;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function download(Request $request)
    {
        try {
            $site = $request->site;
            $nojs = $request->nojs;
            $sdate = $request->sdate;
            $edate = $request->edate;

            $data = [
                "nojs" => $nojs,
                "sdate" => $sdate,
                "edate" => $edate,
            ];

            $tempLogger = NojsLoggersController::sla2($data);
            $logger = $tempLogger['logger']->reverse();
            $result = [];

            $monthly = $logger->groupBy(function ($item, $key) {
                return substr($item["time_local"], 0, 7);
            });
            // dd($monthly);

            foreach ($monthly as $loop => $month) {
                $tempResult = [];
                $sumDuration = 0;
                $sumBattVolt = 0;

                foreach ($month as $key => $value) {
                    $timeNext = $key + 1 < count($month) ? $month[$key + 1]['time_local'] : $value['time_local'];
                    $tempTime =  Carbon::parse($timeNext)->diffInSeconds(Carbon::parse($value['time_local']));
                    $duration = $tempTime > 300 ? 300 : $tempTime;
                    $sumDuration += $duration;
                    $sumBattVolt +=  $value["batt_volt1"];
                    $batt_volt = $value["batt_volt1"]/100;
                    array_push($tempResult, [
                        "date Time" => $value['time_local'],
                        // "nojs" => $value["nojs"],
                        "eh1" => $value['eh1'],
                        "eh2" => $value['eh2'],
                        "vsat curr" => $value['vsat_curr'] / 100,
                        "bts curr" => $value["bts_curr"] / 100,
                        // "load3" => $value["load3"],
                        "batt volt" => $batt_volt,
                        "edl1" => $value["edl1"],
                        "edl2" => $value["edl2"],
                        "lvd1" => (($value['vsat_curr'] / 100) > 0) ? $batt_volt : 0,
                        "lvd2" => (($value['bts_curr'] / 100) > 0) ? $batt_volt : 0,
                        "duration" => $duration,
                        'real' => $tempTime
                    ]);
                }
                array_push($result, [
                    'date' => $loop,
                    'site' => $site,
                    'sum_duration' => $sumDuration,
                    'sum_batt_volt' => count($tempResult),
                    'data' => $tempResult
                ]);
            }
            return Excel::download(new ReportSlaMultipleSheet($result), "$site.xls");
        } catch (\Throwable $th) {
            return redirect('/');
        }
    }
}
