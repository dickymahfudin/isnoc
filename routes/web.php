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

Route::get('/', function () {
    return view('welcome');
})->middleware('verified');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
// Route::redirect('/register', '/home', 301);

Route::group(['prefix' => 'nojs',  'middleware' => 'verified'], function () {
    Route::get('/', 'NojsUsersController@index')->name('nojs.index');
    Route::post('/', 'NojsUsersController@store')->name('nojs.store');
    Route::get('/create', 'NojsUsersController@create')->name('nojs.create');
    Route::get('/{nojsUser}', 'NojsUsersController@show')->name('nojs.show');
    Route::put('/{nojsUser}', 'NojsUsersController@update')->name('nojs.update');
    Route::delete('/{nojsUser}', 'NojsUsersController@destroy')->name('nojs.destroy');
    Route::get('/{nojsUser}/edit', 'NojsUsersController@edit')->name('nojs.edit');
});
Route::get( '/test', 'NojsUsersController@test');

Route::get('/service', 'ServiceCallsController@index');