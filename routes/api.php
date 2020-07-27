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
    Route::get('/detail', 'Api\NojsLoggersController@detail');
    Route::post('/', 'Api\NojsLoggersController@store');
});

Route::prefix('nojs')->group(function () {
    Route::get('/', 'Api\ApiNojsUserController@index');
    Route::get('/error', 'Api\ApiNojsUserController@bbc');
    Route::put('/', 'Api\ApiNojsUserController@update');
});

Route::prefix('prtg')->group(function () {
    Route::get('/', 'Api\PrtgController@getDataTotals')->name('apislaprtg');
    Route::get('/state', 'Api\PrtgController@stateHistory')->name('apistateprtg');
    Route::get('/sla', 'Api\SlaPrtgController@index')->name('prtg.sla.index');
});

Route::prefix('backup')->group(function () {
    Route::post('/', 'Api\BackupLoggersController@store')->name('backuplogger');
});

Route::prefix('raspi')->group(function () {
    Route::get('/', 'Api\QueueRaspisController@index')->name('raspi.index');
    Route::post('/', 'Api\QueueRaspisController@store')->name('raspi.store');
    Route::put('/{queueRaspi}', 'Api\QueueRaspisController@update')->name('raspi.update');
});

Route::prefix('servicecount')->group(function () {
    Route::get('/', 'Api\ServiceCallsDailyController@index');
});

Route::prefix('ajn')->group(function () {
    Route::get('/', 'AjnLoggerController@getData');
});