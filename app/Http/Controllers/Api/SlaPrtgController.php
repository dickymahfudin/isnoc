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
        $data = $this->getSla($nojs);
        return response($data, 200);
    }

    public static function getSla($nojs)
    {
        try {
            // $end = Carbon::now()->format('Y-m-d');
            $end = '2020-07-14';

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
        } catch (\Throwable $th) {
            $data = [
                "daily" => 0,
                "monthly" => 0,
                "log" => 0
            ];
        }
        return $data;
    }

    public static function monthly()
    {
        $users = NojsUser::orderBy('site', 'asc')->get();

        // $sdate = "2020-10-01-00-00";
        // $edate = "2020-11-01-00-00";

        $edate = Carbon::now()->format('Y-m-d-00-00');
        $sdate = (new Carbon($edate))->subMonths(1)->format('Y-m-d-00-00');
        $day = Carbon::parse($edate)->diffInDays(Carbon::parse($sdate)) * 100;
        $username = "Power APT";
        $password = "APT12345";
        $start = (new Carbon($sdate))->format('Y-m-d H:i:s');
        $end = (new Carbon($edate))->format('Y-m-d H:i:s');
        $slaPrtg = [];
        $sla2 = [];
        $kpi1  = 0;
        $kpi2  = 0;
        $kpi3  = [];
        $kpi4  = [];
        $kpi5  = [];
        $sec = 0;
        foreach ($users as $key => $data) {
            $id = $data->id_lvdvsat;
            $new_data = [
                "id" => $id,
                "sdate" => $sdate,
                "edate" => $edate,
                "username" => $username,
                "password" => $password
            ];
            $powerDown = ServiceCallsDailyController::powerDown([
                "start" => trim($sdate, "-00-00"),
                "end" => trim($edate, "-00-00"),
                "nojs" => $data->nojs
            ]);

            $temp = PrtgController::getPrtg($new_data);
            $sla = $temp->original;
            $uptimepercent = $sla["uptimepercent"];
            $uptime = $sla["uptime"];
            $downtime = $sla["downtime"];
            $tempEnergy = $powerDown < 10 ? "0$powerDown"  : $powerDown;
            array_push($slaPrtg, [
                "nojs" => $data->nojs,
                "site" => $data->site,
                "lc" => $data->lc,
                "lvd1_vsat" => $uptimepercent,
                "uptime" => $uptime,
                "downtime" => $downtime,
                "energy" => $tempEnergy  > 0 ? $tempEnergy . "d" . " 00h 00m 00s" : $tempEnergy  . "s"
            ]);
            $kpi1 += $powerDown;

            if ($uptimepercent <= 95) {
                $dataSla2 = NojsLoggersController::sla2([
                    "nojs" => $data->nojs,
                    "sdate" => $start,
                    "edate" => $end
                ]);
                $stringArray = explode(' ', $downtime);
                foreach ($stringArray as $value) {
                    $string = substr($value, 2);
                    $time = intval(trim($value, $string));
                    if ($string === 'd')  $sec += $time * 86400;
                    elseif ($string === 'h')  $sec += $time * 3600;
                    elseif ($string === 'm') $sec += $time * 60;
                    else $sec += $time;
                }

                array_push($sla2, [
                    "detail" => [
                        "nojs" => $data->nojs,
                        "site" => $data->site,
                        "lc" => $data->lc,
                        "sla" => $uptimepercent
                    ],
                    "data" => $dataSla2["daily"]
                ]);
                if ($uptimepercent <= 95 && $uptimepercent >= 91) {
                    array_push($kpi4, $uptimepercent);
                } else {
                    array_push($kpi3, $uptimepercent);
                }
            } else {
                array_push($kpi5, $uptimepercent);
            }
        }

        $collection = collect($slaPrtg);
        $sorted = $collection->sortBy(function ($item) {
            return $item["lvd1_vsat"];
        });

        $collectionSla2 = collect($sla2);
        $sortedSla2 = $collectionSla2->sortBy(function ($item) {
            return $item["detail"]["sla"];
        });

        $kpi2 = round($sec / 86400);

        return response([
            "detail" => [
                "start" => $start,
                "end" => $end,
                "up" => count($slaPrtg) - count($sla2),
                "down" => count($sla2),
                "sec" => $sec / 86400,
                "day" => $day,
            ],
            "kpi" => [
                "kpi1" => [
                    "days" => round($kpi1),
                    "avg" => round((($kpi1 / $day) * 100), 2)
                ],
                "kpi2" => [
                    "days" => round($kpi2),
                    "avg" => round((($kpi2 / $day) * 100), 2)
                ],
                "kpi3" => [
                    "qty" => count($kpi3),
                    "avg" => count($kpi3)  === 0  ? 0 : round(collect($kpi3)->avg(), 1)
                ],
                "kpi4" => [
                    "qty" => count($kpi4),
                    "avg" => count($kpi4) === 0 ? 0 : round(collect($kpi4)->avg(), 1)
                ],
                "kpi5" => [
                    "qty" => count($kpi5),
                    "avg" => count($kpi5) === 0 ? 0 : round(collect($kpi5)->avg(), 1)
                ],
            ],
            "sla_prtg" =>  $sorted->values()->all(),
            "sla_2" => $sortedSla2->values()->all(),
        ], 200);
    }
}