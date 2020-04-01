<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix( 'servicecalls')->group(function () {
    Route::get('/', 'ServiceCallsController@index');
    Route::get( '/{serviceCall}', 'ServiceCallsController@show');
    Route::post('/', 'ServiceCallsController@store');
    Route::put( '/{serviceCall}', 'ServiceCallsController@update');
});

Route::prefix('logger')->group(function (){
    Route::get('/', 'NojsLoggersController@loggers');
    Route::post('/', 'NojsLoggersController@store');
});
