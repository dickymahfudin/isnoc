<?php

namespace App\Http\Controllers;

use App\Models\NojsUser;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NojsUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // if ($request->search) {
        //     $datanojs = NojsUser::where('nojs', 'like', '%' . $request->search . '%')
        //         ->orwhere('site', 'like', '%' . $request->search . '%')
        //         ->orwhere('provinsi', 'like', '%' . $request->search . '%')
        //         ->orwhere('lc', 'like', '%' . $request->search . '%')
        //         ->orwhere('mitra', 'like', '%' . $request->search . '%')
        //         ->orwhere('ip', 'like', '%' . $request->search . '%')
        //         ->orwhere('latitude', 'like', '%' . $request->search . '%')
        //         ->orwhere('longitude', 'like', '%' . $request->search . '%')
        //         ->get();
        //     // dd($datanojs);
        // } else {
        //     $datanojs = NojsUser::all();
        // }
        // return view('nojs.index', [
        //     'datanojs' => $datanojs
        // ]);

        return view('nojs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $datanojs = new NojsUser();
        return view('nojs.form', [
            'datanojs' => $datanojs
        ]);
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
            'provinsi' => 'required|string|max:20',
            'lc' => 'required',
            'mitra' => 'required',
            'ip' => 'required|string|max:15',
            // 'latitude' => 'required|string|max:20',
            // 'longitude' => 'required|string|max:20',
        ]);
        // return $request;

        // $datanojs = new NojsUser;

        // $datanojs->nojs = $request->nojs;
        // $datanojs->site = $request->site;
        // $datanojs->provinsi = $request->provinsi;
        // $datanojs->lc = $request->lc;
        // $datanojs->ip = $request->ip;
        // $datanojs->latitude = $request->latitude;
        // $datanojs->longitude = $request->longitude;

        // $datanojs->save();
        $datanojs = NojsUser::create($request->all());
        return $datanojs;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\NojsUser  $nojsUser
     * @return \Illuminate\Http\Response
     */
    public function show(NojsUser $nojsUser)
    {
        return view('nojs.show', [
            'datanojs' => $nojsUser
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\NojsUser  $nojsUser
     * @return \Illuminate\Http\Response
     */
    public function edit(NojsUser $nojsUser)
    {
        return view('nojs.form', [
            'datanojs' => $nojsUser
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\NojsUser  $nojsUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NojsUser $nojsUser)
    {
        $this->validate($request, [
            'nojs' => 'required',
            'site' => 'required|string|max:20',
            'provinsi' => 'required|string|max:20',
            'lc' => 'required',
            'ip' => 'required|string|max:15',
            // 'latitude' => 'required|string|max:20',
            // 'longitude' => 'required|string|max:20',
        ]);

        $nojsUser->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\NojsUser  $nojsUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(NojsUser $nojsUser)
    {
        $nojsUser->delete();
    }

    public function dataTable()
    {
        $datas = NojsUser::query();
        return datatables()->of($datas)
            ->addColumn('action', function ($datas) {
                return view('nojs._action', [
                    'model' => $datas,
                    'url_show' => route('nojs.show', $datas->nojs),
                    'url_edit' => route('nojs.edit', $datas->nojs),
                    'url_destroy' => route('nojs.destroy', $datas->nojs),
                ]);
            })
            ->AddIndexColumn()
            ->make(true);
    }
}