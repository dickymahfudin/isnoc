<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SlaPrtg;
use Carbon\Carbon;

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
}