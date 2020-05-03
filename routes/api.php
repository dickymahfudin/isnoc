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

Route::prefix('servicecalls')->group(function () {
    Route::get('/', 'Api\ServiceCallsController@index')->name('servicecalls.index');
    Route::get('/{serviceCall}', 'Api\ServiceCallsController@show');
    Route::post('/', 'Api\ServiceCallsController@store');
    Route::put('/{serviceCall}', 'Api\ServiceCallsController@update');
});

Route::prefix('logger')->group(function () {
    Route::get('/', 'Api\NojsLoggersController@loggers')->name('noc.logger');
    Route::post('/', 'Api\NojsLoggersController@store');
});

Route::prefix('nojs')->group(function () {
    Route::get('/', 'Api\ApiNojsUserController@index');
});

Route::prefix('prtg')->group(function () {
    Route::get('/', 'Api\PrtgController@getDataTotals')->name('apislaprtg');
});