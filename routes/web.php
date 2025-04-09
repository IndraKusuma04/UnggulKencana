<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Diskon\DiskonController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Jabatan\JabatanController;
use App\Http\Controllers\Keranjang\KeranjangController;
use App\Http\Controllers\Kondisi\KondisiController;
use App\Http\Controllers\Nampan\NampanController;
use App\Http\Controllers\Nampan\NampanProdukController;
use App\Http\Controllers\Pegawai\PegawaiController;
use App\Http\Controllers\Pelanggan\PelangganController;
use App\Http\Controllers\Pembelian\PembelianController;
use App\Http\Controllers\Produk\JenisProdukController;
use App\Http\Controllers\Produk\ProdukController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Suplier\SuplierController;
use App\Http\Controllers\Transaksi\TransaksiController;

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

    Route::get('/admin/produk', function () {
        return view('Admin.produk');
    });
    Route::get('/admin/produk/getProduk', [ProdukController::class, 'getProduk']);
    Route::post('/admin/produk/storeProduk', [ProdukController::class, 'storeProduk']);
    Route::get('/admin/produk/getProdukByID/{id}', [ProdukController::class, 'getProdukByID']);
    Route::post('/admin/produk/updateProduk/{id}', [ProdukController::class, 'updateProduk']);
    Route::delete('/admin/produk/deleteProduk/{id}', [ProdukController::class, 'deleteProduk']);
    Route::get('/admin/produk/detailProduk/{id}', function () {
        return view('Admin.detailProduk');
    });

    Route::get('/admin/nampan', function () {
        return view('Admin.nampan');
    });
    Route::get('/admin/nampan/getNampan', [NampanController::class, 'getNampan']);
    Route::post('/admin/nampan/storeNampan', [NampanController::class, 'storeNampan']);
    Route::get('/admin/nampan/getNampanByID/{id}', [NampanController::class, 'getNampanByID']);
    Route::post('/admin/nampan/updateNampan/{id}', [NampanController::class, 'updateNampan']);
    Route::delete('/admin/nampan/deleteNampan/{id}', [NampanController::class, 'deleteNampan']);

    Route::get('/admin/nampan/NampanProduk/{id}', function () {
        return view('Admin.nampanProduk');
    });
    Route::get('/admin/nampan/nampanProduk/getNampanProduk/{id}', [NampanProdukController::class, 'getNampanProduk']);
    Route::get('/admin/nampan/nampanProduk/getProdukNampan/{id}', [NampanProdukController::class, 'getProdukNampan']);
    Route::post('/admin/nampan/nampanproduk/storeProdukNampan/{id}', [NampanProdukController::class, 'storeProdukNampan']);
    Route::delete('/admin/nampan/nampanproduk/deleteNampanProduk/{id}', [NampanProdukController::class, 'deleteNampanProduk']);

    Route::get('/admin/scanbarcode', function () {
        return view('Admin.scanbarcode');
    });
    Route::get('/admin/scanbarcode/getProdukByScanbarcode/{id}', [ProdukController::class, 'getProdukByScanbarcode']);

    Route::get('/admin/pelanggan', function () {
        return view('Admin.pelanggan');
    });
    Route::get('/admin/pelanggan/getPelanggan', [PelangganController::class, 'getPelanggan']);
    Route::post('/admin/pelanggan/storePelanggan', [PelangganController::class, 'storePelanggan']);
    Route::get('/admin/pelanggan/getPelangganByID/{id}', [PelangganController::class, 'getPelangganByID']);
    Route::post('/admin/pelanggan/updatePelanggan/{id}', [PelangganController::class, 'updatePelanggan']);
    Route::delete('/admin/pelanggan/deletePelanggan/{id}', [PelangganController::class, 'deletePelanggan']);

    Route::get('/admin/suplier', function () {
        return view('Admin.suplier');
    });
    Route::get('/admin/suplier/getSuplier', [SuplierController::class, 'getSuplier']);
    Route::post('/admin/suplier/storeSuplier', [SuplierController::class, 'storeSuplier']);
    Route::get('/admin/suplier/getSuplierByID/{id}', [SuplierController::class, 'getSuplierByID']);
    Route::post('/admin/suplier/updateSuplier/{id}', [SuplierController::class, 'updateSuplier']);
    Route::delete('/admin/suplier/deleteSuplier/{id}', [SuplierController::class, 'deleteSuplier']);

    Route::get('/admin/pos', function () {
        return view('Admin.pos');
    });
    Route::get('/admin/keranjang/getKeranjang', [KeranjangController::class, 'getKeranjang']);
    Route::get('/admin/keranjang/getKodeKeranjang', [KeranjangController::class, 'getKodeKeranjang']);
    Route::get('/admin/transaksi/getKodeTransaksi', [TransaksiController::class, 'getKodeTransaksi']);
    Route::post('/admin/keranjang/addToCart', [KeranjangController::class, 'addToCart']);
    Route::delete('/admin/keranjang/deleteKeranjangAll', [KeranjangController::class, 'deleteKeranjangAll']);
    Route::delete('/admin/keranjang/deleteKeranjangByID/{id}', [KeranjangController::class, 'deleteKeranjangByID']);
    Route::post('/admin/transaksi/payment', [TransaksiController::class, 'payment']);

    Route::get('/admin/transaksi', function () {
        return view('Admin.transaksi');
    });
    Route::get('/admin/transaksi/getTransaksi', [TransaksiController::class, 'getTransaksi']);
    Route::get('/admin/transaksi/konfirmasiPembayaran/{id}', [TransaksiController::class, 'konfirmasiPembayaran']);
    Route::get('/admin/transaksi/konfirmasiPembatalanPembayaran/{id}', [TransaksiController::class, 'konfirmasiPembatalanPembayaran']);
    Route::get('/admin/transaksi/getTransaksiByID/{id}', [TransaksiController::class, 'getTransaksiByID']);

    Route::get('/admin/pembelian', function () {
        return view('Admin.pembelian');
    });
    Route::get('/admin/pembelian/getPembelian', [PembelianController::class, 'getPembelian']);

    Route::get('/admin/report/cetakBarcodeProduk/{id}', function () {
        return view('Reports.cetakbarcode');
    });
    Route::get('/admin/report/cetakTransaksi/{id}', [ReportController::class, 'cetakTransaksi']);

    Route::get('/logout', [AuthController::class, 'logout']);
});
