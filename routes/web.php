<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Verified;
use Symfony\Component\HttpFoundation\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// })->middleware('verified');

Auth::routes(['verify' => true]);

Route::redirect('/', '/home', 301)->middleware('auth');
// Route::redirect('/register', '/home', 301);

Route::get('/home', 'NocController@index')->name('noc')->middleware('auth');

Route::group(['prefix' => 'nojs', 'middleware' => 'auth'], function () {
    Route::get('/', 'NojsUsersController@index')->name('nojs.index');
    Route::get('/table', 'NojsUsersController@dataTable')->name('nojs.table');
    Route::get('/detail', function () {
        return view('nojs.detail');
    })->name('nojs.detail');
    Route::get('/dataprocessing', function () {
        return view('nojs.dataProcessing');
    })->name('nojs.dataprocessing');
    Route::get('/sla', function () {
        return view('nojs.sla');
    })->name('nojs.sla');
    Route::get('/slacapture', function () {
        return view('nojs.slaCapture');
    })->name('nojs.slacapture');
    Route::post('/', 'NojsUsersController@store')->name('nojs.store');
    Route::get('/create', 'NojsUsersController@create')->name('nojs.create');
    Route::get('/{nojsUser}', 'NojsUsersController@show')->name('nojs.show');
    Route::put('/{nojsUser}', 'NojsUsersController@update')->name('nojs.update');
    Route::delete('/{nojsUser}', 'NojsUsersController@destroy')->name('nojs.destroy');
    Route::get('/{nojsUser}/edit', 'NojsUsersController@edit')->name('nojs.edit');
});

Route::group(['prefix' => 'servicecalls', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('servicecalls.index');
    })->name('servicecalls');
    Route::redirect('/{serviceCall}', '/servicecalls', 301);
    Route::put('/{serviceCall}', 'Api\ServiceCallsController@update')->name('serviceCall.update');
    Route::get('/{serviceCall}/edit', 'Api\ServiceCallsController@edit');
});

Route::group(['prefix' => 'prtg', 'middleware' => 'auth'], function () {
    Route::get('/sla', function () {
        return view('slaprtg.index');
    })->name('sla.prtg')->middleware('auth');

    Route::get('/state', function () {
        return view('slaprtg.state');
    })->name('state.prtg')->middleware('auth');
});

Route::group(['prefix' => 'other', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('other.index');
    })->name('other.index')->middleware('auth');
});

Route::get('/chint', function (Request $request) {
    $url = $request->url;
    $date_start = $request->date_start;
    $date_end = $request->date_end;
    $token = $request->token;
    if ($url) {
        $response = Http::withOptions([
            "headers" => [
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization" => "Token {$token}"
            ],
            "query" => [
                "date_start" => $date_start,
                "date_end" => $date_end
            ]
        ])->get($url);
        return $response->json();
    } else {
        return redirect('/home');
    }
});

Route::group(['prefix' => 'ajn', 'middleware' => 'auth'], function () {
    Route::get('/', 'AjnLoggerController@index')->name('ajn.index');
    Route::post('/', 'AjnLoggerController@store')->name('ajn.store');
    Route::get('/download', 'AjnLoggerController@getSla');
});

Route::group(['prefix' => 'material', 'middleware' => 'auth'], function () {
    Route::get('/', 'MaterialController@index')->name('material.index');
    Route::get('/table', 'MaterialController@dataTable')->name('material.table');
    Route::get('/create', 'MaterialController@create')->name('material.create');
    Route::post('/', 'MaterialController@store')->name('material.store');
    Route::put('/{listMaterial}', 'MaterialController@update')->name('material.update');
    Route::delete('/{listMaterial}', 'MaterialController@destroy')->name('material.destroy');
    Route::get('/{listMaterial}/edit', 'MaterialController@edit')->name('material.edit');
    Route::get('/cadangan', 'MaterialController@getCadangan')->name('material.cadangan');
});


Route::group(['prefix' => 'report', 'middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('report.index');
    })->name('report.index');
    Route::get('/download', 'ReportController@download')->name('report.download');
});
Route::get('/export', 'ReportController@download')->name('report.download');