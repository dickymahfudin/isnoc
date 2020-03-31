<?php

namespace App\Http\Controllers;

use App\Models\NojsLogger;
use Illuminate\Http\Request;

class NojsLoggersController extends Controller
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

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'time_local' => 'required',
            'nojs' => 'required',
            'eh1' => 'required',
            'eh2' => 'required',
            // 'vsat_curr' => 'required',
            // 'bts_curr' => 'required',
            // 'load3' => 'required|string',
            'batt_volt1' => 'required',
            // 'batt_volt2' => 'required',
            'edl1' => 'required',
            'edl2' => 'required',
            'pms_state' => 'required',
        ]);
        $dataLogger = NojsLogger::create($request->all());
        return response($dataLogger,201);
    }

    public function loggers(Request $request)
    {
        $nojs = $request->nojs;
        $limit = $request->limit;

        if ($nojs && $limit) {
            $datas = NojsLogger::where('nojs', $nojs)
                            ->orderBy('time_local', 'desc')
                            ->limit($limit)
                            ->get();
            $data = $this->dataCalculate($datas);
        } else {
            $data = ["Error" => "parameter not found"];
        }
        return response($data, 200);
    }

    public function dataCalculate($datas)
    {
        $valueError = null;
        if (count($datas) != 0)
            if (count($datas) > 1) {
                for ($i = 0; $i < count($datas) - 1; $i++) {
                    $array[$i]['time_local'] = $datas[$i]->time_local;
                    $array[$i]['nojs'] = $datas[$i]->nojs;

                    if (($datas[$i]->eh1 !== $valueError) && ($datas[$i + 1]->eh1 !== $valueError)) {
                        $array[$i]['eh1'] = $datas[$i]->eh1 - $datas[$i + 1]->eh1;
                    } else if (($datas[$i]->eh1 !== $valueError) && ($datas[$i + 1]->eh1 === $valueError)) {
                        $array[$i]['eh1'] =  $this->missedData($i, $datas, 'eh1');
                    } else if (($datas[$i]->eh1 === $valueError) && ($datas[$i + 1]->eh1 !== $valueError) || ($datas[$i]->eh1 == $valueError) && ($datas[$i + 1]->eh1 === $valueError)) {
                        $array[$i]['eh1'] = $valueError;
                    }

                    if (($datas[$i]->eh2 !== $valueError) && ($datas[$i + 1]->eh2 !== $valueError)) {
                        $array[$i]['eh2'] = $datas[$i]->eh2 - $datas[$i + 1]->eh2;
                    } else if (($datas[$i]->eh2 !== $valueError) && ($datas[$i + 1]->eh2 === $valueError)) {
                        $array[$i]['eh2'] =  $this->missedData($i, $datas, 'eh2');
                    } else if (($datas[$i]->eh2 === $valueError) && ($datas[$i + 1]->eh2 !== $valueError) || ($datas[$i]->eh2 === $valueError) && ($datas[$i + 1]->eh2 === $valueError)) {
                        $array[$i]['eh2'] = $valueError;
                    }

                    if (($datas[$i]->batt_volt1 !== $valueError)) {
                        $array[$i]['batt_volt1'] = $datas[$i]->batt_volt1 / 100;
                    } else {
                        $array[$i]['batt_volt1'] = $valueError;
                    }

                    if (($datas[$i]->edl1 !== $valueError) && ($datas[$i + 1]->edl1 !== $valueError)) {
                        $array[$i]['edl1'] = $datas[$i]->edl1 - $datas[$i + 1]->edl1;
                    } else if (($datas[$i]->edl1 !== $valueError) && ($datas[$i + 1]->edl1 === $valueError)) {
                        $array[$i]['edl1'] =  $this->missedData($i, $datas, 'edl1');
                    } else if (($datas[$i]->edl1 === $valueError) && ($datas[$i + 1]->edl1 !== $valueError) || ($datas[$i]->edl1 === $valueError) && ($datas[$i + 1]->edl1 === $valueError)) {
                        $array[$i]['edl1'] = $valueError;
                    }

                    if (($datas[$i]->edl2 !== $valueError) && ($datas[$i + 1]->edl2 !== $valueError)) {
                        $array[$i]['edl2'] = $datas[$i]->edl2 - $datas[$i + 1]->edl2;
                    } else if (($datas[$i]->edl2 !== $valueError) && ($datas[$i + 1]->edl2 === $valueError)) {
                        $array[$i]['edl2'] =  $this->missedData($i, $datas, 'edl2');
                    } else if (($datas[$i]->edl2 === $valueError) && ($datas[$i + 1]->edl2 !== $valueError) || ($datas[$i]->edl2 === $valueError) && ($datas[$i + 1]->edl2 === $valueError)) {
                        $array[$i]['edl2'] = $valueError;
                    }

                    if (($datas[$i]->batt_volt1 !== $valueError)) {
                        $array[$i]['pms_state'] =  $this->pmsconvert($datas[$i]->pms_state);
                    } else {
                        $array[$i]['pms_state'] = $valueError;
                    }
                }
            } else {
                $array['time_local'] = $datas[0]->time_local;
                $array['nojs'] = $datas[0]->nojs;
                $array['eh1'] = (($datas[0]->eh1) != $valueError) ? $datas[0]->eh1 : $valueError;
                $array['eh2'] = (($datas[0]->eh1) != $valueError) ? $datas[0]->eh2 : $valueError;
                $array['batt_volt1'] = (($datas[0]->eh1) != $valueError) ? ($datas[0]->batt_volt1 / 100) : $valueError;
                $array['edl1'] = (($datas[0]->eh1) != $valueError) ? $datas[0]->edl1 : $valueError;
                $array['edl2'] = (($datas[0]->eh1) != $valueError) ? $datas[0]->edl2 : $valueError;
                $array['pms_state'] = (($datas[0]->eh1) != $valueError) ?  pmsConvert($datas[0]->pms_state) : $valueError;

                // $array['batt_volt1'] =  (($datas[0]->batt_volt1$datas[0]) !=$valueError)? $datas[0]->batt_volt1 / 100:$valueError;
                // $array['edl1'] = $datas[0]->edl1;
                // $array['edl2'] = $datas[0]->edl2;
                // $array['pms_state'] =  pmsconvert($datas[0]->pms_state);
            }
        return $array;
    }

    public function pmsConvert($pms)
    {
        $count = 0;
        for ($i = 0; $i < strlen($pms); $i++) {
            if ($pms[$i] == 3) {
                $count += 1;
            }
        }
        return $count;
    }

    public function missedData($loop, $datas, $data)
    {
        $valueError = null;
        $n = 0;

        for ($i = $loop + 1; $i < count($datas) - 1; $i++) {
            $n += 1;
            if ($datas[$i]->$data !=  $valueError) {
                return ($datas[$loop]->$data - $datas[$i]->$data) / $n;
            }
        }
        return $datas[$loop]->$data;
    }
}