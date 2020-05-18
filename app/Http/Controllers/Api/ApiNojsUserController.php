<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NojsUser;

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
        if ($lc && !$lc1) {
            $datas = NojsUser::where('lc', $lc)
                ->get();
        } elseif ($lc && $lc1) {
            $datas = NojsUser::whereIn('lc', array($lc, $lc1))
                ->get();
        } else {
            $datas = NojsUser::all();
        }
        return response($datas, 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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