<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AjnImport;
use App\Models\AjnLogger;
use Carbon\Carbon;
use App\Exports\AjnSlaMultipleSheet;

class AjnLoggerController extends Controller
{
    public function index()
    {
        return view("other.ajn");
    }

    public function store(Request $request)
    {
        $files = $request->file('file');
        if ($request->hasFile('file')) {
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $import = new AjnImport(($filename));
                $import->import($file, null, \Maatwebsite\Excel\Excel::CSV);
            }
            return back()->withStatus("Import Successfully");
        } else {
            return back();
        }
    }

    public function getData(Request $request)
    {
        $site = $request->site;
        $sdate = $request->sdate;
        $edate = $request->edate;

        if ($site && $sdate && $edate) {
            $datas = AjnLogger::select(
                'time_local',
                'site',
                'load1',
                'load2',
                'edl1',
                'edl2',
                'edl3',
                'pv_volt1',
                'pv_curr1',
                'batt_volt1',
                'pv_volt2',
                'pv_curr2',
                'batt_volt2',
            )
                ->where('site', $site)
                ->whereBetween('time_local', [$sdate, $edate])
                ->orderBy('time_local', 'desc')
                ->get();
            $daily = $datas;
            $daily = $this->daily($datas);
            $daily = $this->groupBy('date', $daily);
            $daily = $this->sla($daily);
            return [
                "loggers" => $datas,
                "daily" => $daily
            ];
        } else {
            return [
                'error' => 'Parameter Not Found'
            ];
        }
    }

    public function getSla(Request $request)
    {
        // $site = "toro";
        // $sdate = "2020-05-01 00:00";
        // $edate = "2020-11-24 00:00";
        $site = $request->site;
        $sdate = $request->sdate;
        $edate = $request->edate;

        // if ($site && $sdate && $edate) {
        $datas = AjnLogger::select(
            'time_local',
            'site',
            'load1',
            'load2',
            'edl1',
            'edl2',
            'edl3',
            'pv_volt1',
            'pv_curr1',
            'batt_volt1',
            'pv_volt2',
            'pv_curr2',
            'batt_volt2',
        )
            ->where('site', $site)
            ->whereBetween('time_local', [$sdate, $edate])
            ->orderBy('time_local', 'desc')
            ->get();
        $montly = $datas->groupBy(function ($item, $key) {
            return substr($item["time_local"], 0, 7);
        });
        foreach ($montly as $key => $value) {
            $sla = $value->groupBy(function ($item, $i) {
                return substr($item["time_local"], 0, 10);
            });
            $sla =  $this->sla($sla);
            $montly[$key] = $sla;
        }
        return Excel::download(new AjnSlaMultipleSheet($montly, $site), "$site.xls");
    }

    public function daily($datas)
    {
        $result = [];
        foreach ($datas as $value) {
            $date = (new Carbon($value["time_local"]))->format('Y-m-d');
            array_push($result, [
                'time_local' => $value->time_local,
                'date' => $date,
                'site' => $value->site,
                'load1' => $value->load1,
                'load2' => $value->load2,
                'edl1' => $value->edl1,
                'edl2' => $value->edl2,
                'edl3' => $value->edl3,
                'pv_volt1' => $value->pv_volt1,
                'pv_curr1' => $value->pv_curr1,
                'batt_volt1' => $value->batt_volt1,
                'pv_volt2' => $value->pv_volt2,
                'pv_curr2' => $value->pv_curr2,
                'batt_volt2' => $value->batt_volt2,

            ]);
        };
        return $result;
    }


    public function groupBy($key, $data)
    {
        $result = [];
        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }
        return $result;
    }

    public function sla($datas)
    {

        $result = [];

        foreach ($datas as $value) {
            $time_local = [];
            $load1 = [];
            $load2 = [];
            $edl1 = [];
            $edl2 = [];
            $edl3 = [];
            $pv_volt1 = [];
            $pv_curr1 = [];
            $batt_volt1 = [];
            $pv_volt2 = [];
            $pv_curr2 = [];
            $batt_volt2 = [];
            foreach ($value as $val) {
                $date = (new Carbon($val["time_local"]))->format('Y-m-d');
                array_push($time_local, $val["time_local"]);
                array_push($load1, $val["load1"]);
                array_push($load2, $val["load2"]);
                array_push($edl1, $val["edl1"]);
                array_push($edl2, $val["edl2"]);
                array_push($edl3, $val["edl3"]);
                array_push($pv_volt1, $val["pv_volt1"]);
                array_push($pv_curr1, $val["pv_curr1"]);
                array_push($batt_volt1, $val["batt_volt1"]);
                array_push($pv_volt2, $val["pv_volt2"]);
                array_push($pv_curr2, $val["pv_curr2"]);
                array_push($batt_volt2, $val["batt_volt2"]);
            }
            $time =  Carbon::parse($time_local[0])->diffInSeconds(Carbon::parse($time_local[count($time_local) - 1]));

            array_push($result, [
                'date' => $date,
                'time' => $time,
                'up_time' => gmdate("H:i:s", $time),
                'load1' => intval(round(array_sum($load1) / count($load1))),
                'load2' => intval(round(array_sum($load2) / count($load2))),
                'edl1' => intval(round(array_sum($edl1) / count($edl1))),
                'edl2' => intval(round(array_sum($edl2) / count($edl2))),
                'edl3' => intval(round(array_sum($edl3) / count($edl3))),
                'pv_volt1' => intval(round(array_sum($pv_volt1) / count($pv_volt1))),
                'pv_curr1' => intval(round(array_sum($pv_curr1) / count($pv_curr1))),
                'batt_volt1' => intval(round(array_sum($batt_volt1) / count($batt_volt1))),
                'pv_volt2' => intval(round(array_sum($pv_volt2) / count($pv_volt2))),
                'pv_curr2' => intval(round(array_sum($pv_curr2) / count($pv_curr2))),
                'batt_volt2' => intval(round(array_sum($batt_volt2) / count($batt_volt2))),
            ]);
        }
        return $result;
    }
}