<?php

namespace App\Http\Controllers;

use App\models\ServiceCall;
use Illuminate\Http\Request;

class ServiceCallsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = ServiceCall::all();
        return response($service, 200);
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
        //
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