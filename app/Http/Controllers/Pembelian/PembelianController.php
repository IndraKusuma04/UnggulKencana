<?php

namespace App\Http\Controllers\Pembelian;

use App\Models\Produk;
use App\Models\Keranjang;
use App\Models\Pembelian;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\PembelianProduk;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function generateKodeTransaksiPembelian()
    {
        // Ambil kode customer terakhir dari database
        $lastCode = DB::table('pembelian')
            ->orderBy('kodepembelian', 'desc')
            ->first();

        // Jika tidak ada customer, mulai dari 1
        $lastNumber = $lastCode ? (int) substr($lastCode->kodepembelian, -5) : 0;

        // Tambahkan 1 pada nomor terakhir
        $newNumber = $lastNumber + 1;

        // Format kode customer baru
        $newKodePembelian = '#P-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return $newKodePembelian;
    }

    public function getPembelian()
    {
        $pembelian = Pembelian::with(['suplier', 'pelanggan', 'pembelianproduk', 'user.pegawai'])->get();

        return response()->json(['success' => true, 'message' => 'Data Pembelian Berhasil Ditemukan', 'Data' => $pembelian]);
    }
}
