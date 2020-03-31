<?php

namespace App\Http\Controllers;

use App\models\ServiceCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceCallsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $param = $request->status;
        if ($param) {
            // $service = DB::table( 'service_calls')->where('status', $param )->get();
            $service = DB::table('service_calls')
            ->join('nojs_users', 'service_calls.nojs', '=', 'nojs_users.nojs')
            ->select('service_calls.*', 'nojs_users.site', 'nojs_users.lc')
            ->where('status', 'like', '%' . $request->status . '%')
            ->get();
        }
        else {
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

    /**
     * Display the specified resource.
     *
     * @param  \App\models\ServiceCall  $serviceCall
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceCall $serviceCall)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\ServiceCall  $serviceCall
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceCall $serviceCall)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\ServiceCall  $serviceCall
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceCall $serviceCall)
    {
        $data = $request->validate([
            'closed_time' => 'required',
            'status' => 'required',
        ]);

        $serviceCall->update($data);
        return response($serviceCall, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\ServiceCall  $serviceCall
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceCall $serviceCall)
    {
        //
    }
}
