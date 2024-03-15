<?php

use Illuminate\Support\Facades\Route;

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
});

Route::get('/comp', function () {
    return view('user.complaint');
});
Route::get('/dash', function () {
    return view('user.dashboard');
});
Route::get('/form-comp', function () {
    return view('user.form-comp');
});
Route::get('/test', function () {
    return view('test');
});
Route::get('/it-comp', function () {
    return view('it.it-complaint');
});
Route::get('/detail-comp', function () {
    return view('it.detail-complaint');
});

