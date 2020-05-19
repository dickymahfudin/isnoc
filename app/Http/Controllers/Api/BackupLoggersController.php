<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BackupLogger;

class BackupLoggersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'time_local' => 'required',
            'nojs' => 'required'
        ]);
        $dataLogger = BackupLogger::create($request->all());
        return response($dataLogger, 201);
    }
}