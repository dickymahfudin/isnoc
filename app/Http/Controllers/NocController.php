<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NojsUser;

class NocController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('noc.index');
    }

    public function datauser()
    {
        return NojsUser::all();
    }
}