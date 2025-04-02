<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Diskon\DiskonController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Jabatan\JabatanController;
use App\Http\Controllers\Kondisi\KondisiController;
use App\Http\Controllers\Pegawai\PegawaiController;
use App\Http\Controllers\Produk\JenisProdukController;

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
Route::post('/login/pushLogin', [AuthController::class, 'login']);

// Mencegah user yang sudah login kembali ke halaman login
Route::middleware(['checkRole'])->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
});

// Route untuk masing-masing role
Route::middleware(['checkRole:admin'])->group(function () {
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

    Route::get('/admin/role', function () {
        return view('Admin.role');
    });
    Route::get('/admin/role/getRole', [RoleController::class, 'getRole']);
    Route::post('/admin/role/storeRole', [RoleController::class, 'storeRole']);
    Route::get('/admin/role/getRoleByID/{id}', [RoleController::class, 'getRoleByID']);
    Route::post('/admin/role/updateRole/{id}', [RoleController::class, 'updateRole']);
    Route::delete('/admin/role/deleteRole/{id}', [RoleController::class, 'deleteRole']);

    Route::get('/admin/users', function () {
        return view('Admin.users');
    });
    Route::get('/admin/users/getUsers', [UsersController::class, 'getUsers']);
    Route::get('/admin/users/getUsersByID/{id}', [UsersController::class, 'getUsersByID']);
    Route::post('/admin/users/updateUsers/{id}', [UsersController::class, 'updateUsers']);

    Route::get('/admin/kondisi', function () {
        return view('Admin.kondisi');
    });
    Route::get('/admin/kondisi/getKondisi', [KondisiController::class, 'getKondisi']);
    Route::post('/admin/kondisi/storeKondisi', [KondisiController::class, 'storeKondisi']);
    Route::get('/admin/kondisi/getKondisiByID/{id}', [KondisiController::class, 'getKondisiByID']);
    Route::post('/admin/kondisi/updateKondisi/{id}', [KondisiController::class, 'updateKondisi']);
    Route::delete('/admin/kondisi/deleteKondisi/{id}', [KondisiController::class, 'deleteKondisi']);

    Route::get('/admin/jenisproduk', function () {
        return view('Admin.jenisproduk');
    });
    Route::get('/admin/jenisproduk/getJenisProduk', [JenisProdukController::class, 'getJenisProduk']);
    Route::post('/admin/jenisproduk/storeJenisProduk', [JenisProdukController::class, 'storeJenisProduk']);
    Route::get('/admin/jenisproduk/getJenisProdukByID/{id}', [JenisProdukController::class, 'getJenisProdukByID']);
    Route::post('/admin/jenisproduk/updateJenisProduk/{id}', [JenisProdukController::class, 'updateJenisProduk']);
    Route::delete('/admin/jenisproduk/deleteJenisProduk/{id}', [JenisProdukController::class, 'deleteJenisProduk']);

    Route::get('/admin/diskon', function () {
        return view('Admin.diskon');
    });
    Route::get('/admin/diskon/getDiskon', [DiskonController::class, 'getDiskon']);
    Route::post('/admin/diskon/storeDiskon', [DiskonController::class, 'storeDiskon']);
    Route::get('/admin/diskon/getDiskonByID/{id}', [DiskonController::class, 'getDiskonByID']);
    Route::post('/admin/diskon/updateDiskon/{id}', [DiskonController::class, 'updateDiskon']);
    Route::delete('/admin/diskon/deleteDiskon/{id}', [DiskonController::class, 'deleteDiskon']);

    Route::get('/logout', [AuthController::class, 'logout']);
});
