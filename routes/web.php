<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Verified;

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
    Route::post('/', 'NojsUsersController@store')->name('nojs.store');
    Route::get('/create', 'NojsUsersController@create')->name('nojs.create');
    Route::get('/{nojsUser}', 'NojsUsersController@show')->name('nojs.show');
    Route::put('/{nojsUser}', 'NojsUsersController@update')->name('nojs.update');
    Route::delete('/{nojsUser}', 'NojsUsersController@destroy')->name('nojs.destroy');
    Route::get('/{nojsUser}/edit', 'NojsUsersController@edit')->name('nojs.edit');
});

Route::get('/servicecalls', function () {
    return view('servicecalls.index');
})->name('servicecalls')->middleware('auth');

Route::group(['prefix' => 'prtg', 'middleware' => 'auth'], function () {
    Route::get('/sla', function () {
        return view('slaprtg.index');
    })->name('sla.prtg')->middleware('auth');

    Route::get('/state', function () {
        return view('slaprtg.state');
    })->name('state.prtg')->middleware('auth');
});