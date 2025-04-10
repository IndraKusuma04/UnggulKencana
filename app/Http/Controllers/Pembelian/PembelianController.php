<?php

namespace App\Http\Controllers\Pembelian;

use App\Models\Pembelian;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Produk;

class PembelianController extends Controller
{
    public function getPembelian()
    {
        $pembelian = Pembelian::with(['suplier', 'pelanggan', 'pembelianproduk'])->get();

        return response()->json(['success' => true, 'message' => 'Data Pembelian Berhasil Ditemukan', 'Data' => $pembelian]);
    }

    public function getTransaksiByKodeTransaksi(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
        ];

        $credentials = $request->validate([
            'kodetransaksi'       => 'required',
        ], $messages);

        $transaksi = Transaksi::with(['keranjang' => function ($query) {
            $query->where('status', '!=', 0);
        }, 'keranjang.produk', 'pelanggan', 'user', 'user.pegawai', 'diskon'])->where('kodetransaksi', $request->kodetransaksi)->get();

        // Cek apakah data transaksi ditemukan
        if ($transaksi->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Transaksi Berhasil Ditemukan', 'Data' => $transaksi]);
    }

    public function generateKodePembelianProduk()
    {
        // Cek apakah ada kode keranjang terakhir dengan status 1
        $lastKeranjangWithStatusOne = DB::table('pembelian_produk')
            ->where('status', 1)
            ->orderBy('kodepembelianproduk', 'desc')
            ->first();

        // Jika ada kode keranjang dengan status 1, gunakan kode itu
        if ($lastKeranjangWithStatusOne) {
            return $lastKeranjangWithStatusOne->kodepembelianproduk;
        }

        // Jika tidak ada keranjang dengan status 1, ambil kode keranjang terakhir secara umum
        $lastKeranjang = DB::table('pembelian_produk')
            ->orderBy('kodepembelianproduk', 'desc')
            ->first();

        // Jika tidak ada keranjang sama sekali, mulai dari 1
        $lastNumber = $lastKeranjang ? (int) substr($lastKeranjang->kodepembelianproduk, -5) : 0;

        // Tambahkan 1 pada nomor terakhir
        $newNumber = $lastNumber + 1;

        // Format kode keranjang baru
        $newKodeKeranjang = '#PO-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return $newKodeKeranjang;
    }

    public function storeProdukToPembelianProduk(Request $request)
    {
        $produk = Produk::where('kodeproduk', $request->id)->first();
    }
}
