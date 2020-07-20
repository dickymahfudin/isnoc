<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCallDailys;
use Illuminate\Support\Carbon;

class ServiceCallsDailyController extends Controller
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
        if ($request->weekly) {
            // $today = Carbon::now()->format('Y-m-d');
            $today = '2020-07-14';
            $end = (new Carbon($today))->subDays(1)->format('Y-m-d');
            $start = (new Carbon($end))->subDays(6)->format('Y-m-d');
        }
        $datas = ServiceCallDailys::whereBetween('time_local', [$start,  $end])
            ->orderBy('time_local', 'asc')
            ->get()
            ->groupBy('time_local');
        $index = 0;
        foreach ($datas as $data) {
            $temps = $data;
            $error = 0;
            foreach ($temps as $temp) {
                $date = $temp->time_local;
                $error += $temp->error;
            }
            $array[$index]["time_local"] = $date;
            $array[$index]["sum"] = $error;
            $index++;
        }
        $response = [
            "data_logs" => $datas,
            "sum" => $array,
        ];
        return response($response, 200);
    }

    public static function weekly()
    {
        $today = Carbon::now()->format('Y-m-d');
        // $today = '2020-07-14';
        $end = (new Carbon($today))->subDays(1)->format('Y-m-d');
        $start = (new Carbon($end))->subDays(6)->format('Y-m-d');
        $datas = ServiceCallDailys::whereBetween('time_local', [$start,  $end])
            ->orderBy('time_local', 'asc')
            ->get()
            ->groupBy('time_local');
        $index = 0;
        foreach ($datas as $data) {
            $temps = $data;
            $error = 0;
            foreach ($temps as $temp) {
                $date = $temp->time_local;
                $error += $temp->error;
            }
            $array[$index]["time_local"] = $date;
            $array[$index]["sum"] = $error;
            $index++;
        }
        $response = [
            "data_logs" => $datas,
            "sum" => $array,
        ];
        return $response;
    }
}