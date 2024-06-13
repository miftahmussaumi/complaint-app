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

Route::get('/', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
});
Route::post('/regist-pelapor', [PelaporController::class, 'store'])->name('regist-pelapor');
Route::post('/postlogin', [LoginController::class, 'postlogin'])->name('postlogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

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
    Route::get('/edit-comp/{idlap}', [PelaporController::class, 'edit']);
    Route::post('/update-comp/{idlap}', [PelaporController::class, 'updateLap'])->name('update-comp');
    Route::post('/proses-tambah-waktu/{idlap}', [PelaporController::class, 'tambahwaktu'])->name('proses-tambah-waktu');
    Route::post('/acc-laporan/{idlap}', [PelaporController::class, 'acclap'])->name('acc-laporan');
    Route::match(['get', 'post'], '/history-user',[PelaporController::class, 'history'])->name('history-user');
    Route::post('/delete-laporan/{idlap}', [LaporanController::class, 'delete'])->name('delete-laporan');
    Route::get('/getNoInventarisOptions', [LaporanController::class, 'getNoInventarisOptions']);
    Route::get('/password', [PelaporController::class, 'password']);
    Route::post('/ubah-password', [PelaporController::class, 'ubahpassword'])->name('ubah-password');


    // ==============ROUTE UNTUK BAGIAN ADMIN============== //
    Route::get('/laporan-admin', [AdminController::class, 'index']);
    Route::get('/detail-laporan-admin/{id}', [AdminController::class, 'detail']);
    Route::post('/pilih-teknisi/{id}', [AdminController::class, 'teknisi'])->name('pilih-teknisi');
    Route::post('/manager/{id}', [AdminController::class, 'sendtoManager'])->name('manager');
    Route::get('/dashboard-admin', [AdminController::class, 'dashboard']);
    Route::get('/send-manager', [AdminController::class, 'manager']);
    Route::match(['get', 'post'],'/history-admin', [AdminController::class, 'history'])->name('history-admin');
    Route::get('/list-akun-admin', [AdminController::class, 'akun']);
    // Route::get('/edit-pelapor/{id}', [AdminController::class, 'pelapor']);
    Route::post('/edit-pelapor-save/{id}', [AdminController::class, 'editpelapor'])->name('edit-pelapor-save');
    Route::post('/edit-teknisi-save/{id}', [AdminController::class, 'editteknisi'])->name('edit-teknisi-save');


    // ==============ROUTE UNTUK BAGIAN TEKNISI IT============== //
    Route::get('/profile-teknisi', [TeknisiController::class, 'profile']);
    Route::post('/save-ttd-teknisi/{id}', [TeknisiController::class, 'ttd'])->name('save-ttd-teknisi');;
    Route::get('/layanan-it', [TeknisiController::class, 'index']); //All data
    Route::get('/it', [TeknisiController::class, 'index2']);
    Route::get('/detail-comp-it/{id}', [TeknisiController::class, 'detail'])->name('detail-comp-it');
    Route::post('/proses-laporan/{id}',[TeknisiController::class, 'proses'])->name('proses-laporan');
    Route::post('/reset-waktu/{id}', [TeknisiController::class, 'resetwaktu'])->name('reset-waktu');
    Route::post('/tambah-waktu/{id}', [TeknisiController::class, 'tambahwaktu'])->name('tambah-waktu');
    Route::post('/laporan-selesai-it/{id}', [TeknisiController::class, 'selesai'])->name('laporan-selesai-it');
    Route::match(['get', 'post'], '/history-it', [TeknisiController::class, 'history'])->name('history-it');
    Route::get('/getNoInventarisOptionsIT', [TeknisiController::class, 'getNoInventarisOptionsIT']);
    Route::post('/detail-pekerjaan-it/{id_det}', [TeknisiController::class, 'pekerjaanIT'])->name('detail-pekerjaan-it');



    // ==============ROUTE UNTUK BAGIAN PENGAWAS/MANAGER============== //
    Route::get('/dashboard-pengawas', function () {return view('pengawas.dashboard');});
    Route::get('/profile-pengawas', [PengawasController::class, 'profile']);
    Route::post('/save-ttd-pengawas/{id}', [PengawasController::class, 'ttd'])->name('save-ttd-pengawas');;
    Route::get('/list-akun', [PengawasController::class, 'akun']);
    Route::get('/list-laporan', [PengawasController::class, 'laporan']);
    Route::post('/acc-akun/{id}', [PengawasController::class, 'accakun'])->name('acc-akun');
    Route::get('/cetak-laporan/{idlap}', [PengawasController::class, 'cetak'])->name('cetak-laporan');
    Route::get('/detail-laporan/{id}', [PengawasController::class, 'detail'])->name('detail-laporan');
    Route::post('/laporan-akhir/{idlap}', [LaporanakhirController::class, 'store'])->name('laporan-akhir');
    Route::get('/cetak', function () {return view('pengawas.cetak');});

    

});

