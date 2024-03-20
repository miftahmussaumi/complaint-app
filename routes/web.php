<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelaporController;
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

Route::post('/regist-pelapor', [PelaporController::class, 'store'])->name('regist-pelapor');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/postlogin', [LoginController::class, 'postlogin'])->name('postlogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/home', function () {
    return view('pelapor.dashboard');
});
Route::get('/form-comp', function () {
    return view('pelapor.comp-create');
});
Route::get('/comp', [LaporanController::class, 'index']);
Route::post('/save-laporan', [LaporanController::class, 'store'])->name('save-laporan');

Route::get('/it-comp', function () {
    return view('it.it-complaint');
});
Route::get('/detail-comp', function () {
    return view('it.detail-complaint');
});
// Route::group(['middleware' => ['auth:admin,pelapor,pengawas']], function () {
// });

