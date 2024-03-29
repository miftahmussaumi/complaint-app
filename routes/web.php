<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelaporController;
use App\Http\Controllers\PengawasController;
use App\Models\Pengawas;
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
Route::get('/test', function () {
    return view('test');
});

Route::group(['middleware' => ['auth:admin,pelapor,pengawas']], function () {
    // ==============ROUTE UNTUK BAGIAN USER PELAPOR============== //
    Route::get('/dashboard-user', [LaporanController::class, 'dashU']);
    Route::get('/form-comp', function () { return view('pelapor.comp-create');});
    Route::get('/comp', [LaporanController::class, 'index']);
    Route::post('/save-laporan', [LaporanController::class, 'store'])->name('save-laporan');
    Route::post('/laporan-update/{idlap}', [LaporanController::class, 'update'])->name('laporan-update');
    Route::match(['get', 'post'], '/history-user',[LaporanController::class, 'historyU'])->name('history-user');
    // Route::get('/history-user', [LaporanController::class, 'historyU'])->name('history-user');
    Route::get('/delete-laporan/{idlap}', [LaporanController::class, 'delete'])->name('delete-laporan');
    Route::get('/getNoInventarisOptions', [LaporanController::class, 'getNoInventarisOptions']);
    Route::get('/getKategoriOptions', [LaporanController::class, 'getKategoriOptions']);

    // ==============ROUTE UNTUK BAGIAN USER IT============== //
    Route::get('/it', [LaporanController::class, 'indexit']);
    Route::post('/proses-laporan/{idlap}',[LaporanController::class, 'editit'])->name('proses-laporan');
    Route::post('/tambah-waktu/{idlap}', [LaporanController::class, 'tambahwaktu'])->name('tambah-waktu');
    Route::get('/comp-detail/{idlap}', [LaporanController::class, 'detail'])->name('comp-detail');
    Route::post('/pelayanan-selesai/{idlap}', [LaporanController::class, 'pelayananS'])->name('pelayanan-selesai');
    Route::get('/history-admin/{id}', [LaporanController::class, 'historyA']);

    // ==============ROUTE UNTUK BAGIAN USER PENGAWAS============== //
    Route::get('/dashboard-pengawas', function () {
        return view('pengawas.dashboard');
    });
    Route::get('/list-akun', [PengawasController::class, 'akun']);
    Route::get('/list-laporan', [PengawasController::class, 'laporan']);
    Route::post('/pilih-pjuser/{id}', [PengawasController::class, 'pjuser'])->name('pilih-pjuser');
    Route::get('/cetak-laporan/{idlap}', [PengawasController::class, 'cetak'])->name('cetak-laporan');
    Route::get('/cetak', function () {
        return view('pengawas.cetak');
    });
});

