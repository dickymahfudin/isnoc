<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListMaterial;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('listmaterials.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $listMaterial = new ListMaterial();
        return view('listmaterials.form', [
            'listMaterial' => $listMaterial
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
            'nama_barang' => 'required',
            'jumlah_barang' => 'required',
            'mitra' => 'required',
            'status' => 'required',
        ]);

        $datanojs = ListMaterial::create($request->all());
        return $datanojs;
    }

    // public function show(NojsUser $nojsUser)
    // {
    //     return view('nojs.show', [
    //         'datanojs' => $nojsUser
    //     ]);
    // }

    public function edit(ListMaterial $listMaterial)
    {
        return view('listmaterials.form', [
            'listMaterial' => $listMaterial
        ]);
    }

    public function update(Request $request, ListMaterial $listMaterial)
    {
        $listMaterial->update($request->all());
    }

    public function destroy(ListMaterial $listMaterial)
    {
        $listMaterial->delete();
    }

    public function dataTable()
    {
        $datas = ListMaterial::all();
        return datatables()->of($datas)
            ->addColumn('action', function ($datas) {
                return view('listmaterials._action', [
                    'model' => $datas,
                    'url_edit' => route('material.edit', $datas->id),
                    'url_destroy' => route('material.destroy', $datas->id),
                ]);
            })
            ->AddIndexColumn()
            ->make(true);
    }

    public function getCadangan()
    {

        $result = ListMaterial::where([
            ['status', 'KELUAR'],
            ['tanggal_pemasangan', null]
        ])
            ->get();
        return response($result, 200);
    }
}