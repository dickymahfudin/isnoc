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
    // return view('auth.login');
});
Route::get('/x', function () {
    return view('auth.logintest');
    // return view('auth.login');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');