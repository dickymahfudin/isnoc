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


Route::get('/servicecalls', 'ServiceCallsController@index');
Route::get( '/servicecalls/{serviceCall}', 'ServiceCallsController@show');
Route::post('/servicecalls', 'ServiceCallsController@store');
Route::put( '/servicecalls/{serviceCall}', 'ServiceCallsController@update');


Route::get('/logger', 'NojsLoggersController@loggers');
Route::post('/logger', 'NojsLoggersController@store');
