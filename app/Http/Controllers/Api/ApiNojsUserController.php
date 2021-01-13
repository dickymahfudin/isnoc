<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NojsUser;
use function GuzzleHttp\json_encode;

class ApiNojsUserController extends Controller
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
        $lc = $request->lc;
        $lc1 = $request->lc1;
        $noc = $request->noc;
        $start = $request->start;
        $take = $request->take;

        if ($lc && !$lc1) {
            $datas = NojsUser::where('lc', $lc)
                ->get();
        } elseif ($lc && $lc1) {
            $datas = NojsUser::whereIn('lc', array($lc, $lc1))
                ->get();
        } elseif ($noc === "site1") {
            $datas = NojsUser::where('ehub_version', '!=', true)
                ->orderBy('lc', 'asc')
                ->orderBy('nojs', 'asc')
                ->skip(0)
                ->take(25)
                ->get();
        } elseif ($noc === "site2") {
            $datas = NojsUser::where('ehub_version', '!=', true)
                ->orderBy('lc', 'asc')
                ->orderBy('nojs', 'asc')
                ->skip(25)
                ->take(25)
                ->get();
        } elseif ($noc === "site3") {
            $datas = NojsUser::where('ehub_version', '!=', true)
                ->orderBy('lc', 'asc')
                ->orderBy('nojs', 'asc')
                ->skip(50)
                ->take(25)
                ->get();
        } elseif ($noc === "site4") {
            $datas = NojsUser::where('ehub_version', '!=', true)
                ->orderBy('lc', 'asc')
                ->orderBy('nojs', 'asc')
                ->skip(75)
                ->take(25)
                ->get();
        } elseif ($start === 0 && $take) {
            $datas = NojsUser::where('ehub_version', '!=', true)
                ->orderBy('lc', 'asc')
                ->orderBy('nojs', 'asc')
                ->skip(0)
                ->take($take)
                ->get();
        } elseif ($start !== 0 && $take) {
            $datas = NojsUser::where('ehub_version', '!=', true)
                ->orderBy('lc', 'asc')
                ->orderBy('nojs', 'asc')
                ->skip($start)
                ->take($take)
                ->get();
        } else {
            $datas = NojsUser::where('ehub_version', '!=', true)->orderBy('site', 'asc')->get();
        }
        return response($datas, 200);
    }

    public function bbc(Request $request)
    {
        $siteerror = NojsUser::where('no_urut', 1)
            ->get();
        $lc = $request->lc;
        $temp = [];
        if ($lc) {
            $datas = NojsUser::where('lc', $lc)
                ->get();
            foreach ($datas as $data) {
                foreach ($siteerror as $site) {
                    if ($data['nojs'] === $site['nojs']) {
                        $ip = explode('.', $data->ip);
                        $ipbbc = intval($ip[3]) + 1;
                        $temp1 = $ip[0] . "." . $ip[1] . "." . $ip[2] . "." . $ipbbc;
                        array_push($temp, [
                            "nojs" => $data->nojs,
                            "site" => $data->site,
                            "lc" => $data->lc,
                            "ip" => $data->ip,
                            "ip_bbc" => $temp1
                        ]);
                    }
                }
            }
        }
        return response($temp, 200);
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
            'nojs' => 'required|unique:nojs_users,nojs',
            'site' => 'required|string|max:20',
            // 'provinsi' => 'required|string|max:20',
            'lc' => 'required',
            'mitra' => 'required',
            'ip' => 'required|string|max:15',
        ]);

        $datanojs = NojsUser::create($request->all());
        return $datanojs;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(NojsUser $nojsUser)
    {
        return ($nojsUser);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, NojsUser $nojsUser)
    {
        $this->validate($request, [
            'nojs' => 'required',
            'site' => 'required|string|max:20',
            'provinsi' => 'required|string|max:20',
            'lc' => 'required',
            'ip' => 'required|string|max:15',

        ]);

        $result = $nojsUser->update($request->all());
        return response($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->data;
        if ($data) {
            foreach ($data as $value) {
                $new_data = ([
                    "provinsi" => $value["provinsi"],
                    "mitra" => $value["mitra"],
                    "latitude" => $value["latitude"],
                    "longitude" => $value["longitude"],
                    "id_lvdvsat" => $value["id_lvdvsat"],
                    "id_ping" => $value["id_ping"],
                    "id_batvolt" => $value["id_batvolt"],
                    "id_vsatcurr" => $value["id_vsatcurr"],
                    "id_btscurr" => $value["id_btscurr"],
                ]);
                NojsUser::where('nojs', $value["nojs"])
                    ->update($new_data);
            }
            $response = [
                "data" => count($data),
                "response" => 200
            ];
        } else {
            $response = [
                "data" => "Invalid Parameter",
                "response" => 502
            ];
        }

        return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}