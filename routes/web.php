<?php

use App\Http\Controllers\Jabatan\JabatanController;
use App\Http\Controllers\Pegawai\PegawaiController;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/admin/dashboard', function () {
    return view('Admin.dashboard');
});

Route::get('/admin/jabatan', function () {
    return view('Admin.jabatan');
});
Route::get('/admin/jabatan/getJabatan', [JabatanController::class, 'getJabatan']);
Route::post('/admin/jabatan/storeJabatan', [JabatanController::class, 'storeJabatan']);
Route::get('/admin/jabatan/getJabatan/{id}', [JabatanController::class, 'getJabatanByID']);
Route::post('/admin/jabatan/updateJabatan/{id}', [JabatanController::class, 'updateJabatan']);
Route::delete('/admin/jabatan/deleteJabatan/{id}', [JabatanController::class, 'deleteJabatan']);

Route::get('/admin/pegawai', function () {
    return view('Admin.pegawai');
});
Route::get('/admin/pegawai/getPegawai', [PegawaiController::class, 'getPegawai']);
Route::post('/admin/pegawai/storePegawai', [PegawaiController::class, 'storePegawai']);
Route::get('/admin/pegawai/getPegawaiByID/{id}', [PegawaiController::class, 'getPegawaiByID']);
Route::post('/admin/pegawai/updatePegawai/{id}', [PegawaiController::class, 'updatePegawai']);
Route::delete('/admin/pegawai/deletePegawai/{id}', [PegawaiController::class, 'deletePegawai']);
