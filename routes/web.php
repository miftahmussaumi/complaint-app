<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanakhirController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PelaporController;
use App\Http\Controllers\PengawasController;
use App\Http\Controllers\TeknisiController;
use App\Models\Pengawas;
use App\Models\Teknisi;
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
Route::get('/test3', function () {
    return view('test3');
});

Route::group(['middleware' => ['auth:admin,pelapor,pengawas,teknisi']], function () {
    // ==============ROUTE UNTUK BAGIAN USER PELAPOR============== //
    Route::get('/profile-pelapor', [PelaporController::class, 'profile']);
    Route::post('/save-ttd-pelapor/{id}', [PelaporController::class, 'ttd'])->name('save-ttd-pelapor');;
    Route::post('/save-foto-pelapor/{id}', [PelaporController::class, 'foto'])->name('save-foto-pelapor');;
    Route::get('/dashboard-user', [LaporanController::class, 'dashU']);
    Route::get('/form-comp', function () { return view('pelapor.comp-create');});
    Route::post('/save-laporan', [LaporanController::class, 'store'])->name('save-laporan');
    Route::get('/comp', [PelaporController::class, 'index']);
    Route::get('/detail-comp/{idlap}', [PelaporController::class, 'detail']);
    Route::post('/proses-tambah-waktu/{idlap}', [PelaporController::class, 'tambahwaktu'])->name('proses-tambah-waktu');
    Route::post('/acc-laporan/{idlap}', [PelaporController::class, 'acclap'])->name('acc-laporan');
    Route::match(['get', 'post'], '/history-user',[PelaporController::class, 'history'])->name('history-user');
    Route::get('/delete-laporan/{idlap}', [LaporanController::class, 'delete'])->name('delete-laporan');
    Route::get('/getNoInventarisOptions', [LaporanController::class, 'getNoInventarisOptions']);


    // ==============ROUTE UNTUK BAGIAN ADMIN============== //
    Route::get('/laporan-admin', [AdminController::class, 'index']);
    Route::get('/detail-laporan-admin/{id}', [AdminController::class, 'detail']);
    Route::post('/pilih-teknisi/{id}', [AdminController::class, 'teknisi'])->name('pilih-teknisi');
    Route::post('/manager/{id}', [AdminController::class, 'sendtoManager'])->name('manager');



    // ==============ROUTE UNTUK BAGIAN TEKNISI IT============== //
    Route::get('/profile-teknisi', [TeknisiController::class, 'profile']);
    Route::post('/save-ttd-teknisi/{id}', [TeknisiController::class, 'ttd'])->name('save-ttd-teknisi');;
    Route::get('/it', [TeknisiController::class, 'index']);
    Route::get('/detail-comp-it/{id}', [TeknisiController::class, 'detail'])->name('detail-comp-it');
    Route::post('/proses-laporan/{id}',[TeknisiController::class, 'proses'])->name('proses-laporan');
    Route::post('/tambah-waktu/{id}', [TeknisiController::class, 'tambahwaktu'])->name('tambah-waktu');
    Route::post('/laporan-selesai-it/{id}', [TeknisiController::class, 'selesai'])->name('laporan-selesai-it');
    Route::match(['get', 'post'], '/history-it', [TeknisiController::class, 'history'])->name('history-it');
    Route::get('/getNoInventarisOptionsIT', [TeknisiController::class, 'getNoInventarisOptionsIT']);
    Route::post('/detail-pekerjaan-it/{id}', [TeknisiController::class, 'pekerjaanIT'])->name('detail-pekerjaan-it');



    // ==============ROUTE UNTUK BAGIAN PENGAWAS/MANAGER============== //
    Route::get('/dashboard-pengawas', function () {return view('pengawas.dashboard');});
    Route::get('/profile-pengawas', [PengawasController::class, 'profile']);
    Route::post('/save-ttd-pengawas/{id}', [PengawasController::class, 'ttd'])->name('save-ttd-pengawas');;
    Route::get('/list-akun', [PengawasController::class, 'akun']);
    Route::get('/list-laporan', [PengawasController::class, 'laporan']);
    Route::post('/pilih-pjuser/{id}', [PengawasController::class, 'pjuser'])->name('pilih-pjuser');
    Route::get('/cetak-laporan/{idlap}', [PengawasController::class, 'cetak'])->name('cetak-laporan');
    Route::get('/detail-laporan/{id}', [PengawasController::class, 'detail'])->name('detail-laporan');
    Route::post('/laporan-akhir/{idlap}', [LaporanakhirController::class, 'store'])->name('laporan-akhir');
    Route::get('/cetak', function () {return view('pengawas.cetak');});

    

});

