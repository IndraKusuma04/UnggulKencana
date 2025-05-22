<?php

namespace App\Http\Controllers\Pembelian;

use Carbon\Carbon;
use App\Models\Produk;
use App\Models\Keranjang;
use App\Models\Pembelian;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\PembelianProduk;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Perbaikan;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function generateKodeTransaksiPembelian()
    {
        $now = Carbon::now()->format('YmdHis');

        // Random 3 digit (untuk menghindari duplikasi pada timestamp yang sama)
        $random = rand(100, 999);

        // Ambil urutan terakhir jika ingin tetap menyimpan pola urutan
        $last = DB::table('pembelian')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = $last ? $last->id + 1 : 1;

        // Format akhir kode
        $kode = 'PMB-' . $now . '-' . $random . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        return $kode;
    }

    public function getPembelian()
    {
        $pembelian = Pembelian::with(['suplier', 'pelanggan', 'pembelianproduk', 'user.pegawai'])->get();

        return response()->json(['success' => true, 'message' => 'Data Pembelian Berhasil Ditemukan', 'Data' => $pembelian]);
    }

    public function getPembelianByID($id)
    {
        $pembelian = Pembelian::with([
            'suplier',
            'pelanggan',
            'pembelianproduk' => function ($query) {
                $query->where('status', '!=', 0);
            },
            'user.pegawai'
        ])->where('id', $id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Pembelian Berhasil Ditemukan',
            'Data' => $pembelian
        ]);
    }

    public function konfirmasiPembelian($id)
    {
        $pembelian  = Pembelian::where('id', $id)
            ->update([
                'status' => 2,
            ]);

        return response()->json(['success' => true, 'message' => 'Pembelian Berhasil Dikonfirmasi']);
    }

    public function konfirmasiPembatalanPembelian($id)
    {
        $pembelian = Pembelian::where('id', $id)->first();

        $kodepembelianproduk = $pembelian->kodepembelianproduk;
        $kodeproduk = PembelianProduk::where('kodepembelianproduk', $kodepembelianproduk)
            ->pluck('kodeproduk')
            ->toArray();

        $idproduk = Produk::whereIn('kodeproduk', $kodeproduk)
            ->pluck('id')
            ->toArray();

        $cancel = Pembelian::where('id', $id)
            ->update([
                'status'   =>   0,
            ]);

        if ($cancel) {
            Perbaikan::whereIn('produk_id', $idproduk)
                ->update([
                    'status'        => 0,
                    'keterangan'    => "BATAL TRANSAKSI PADA TANGGAL " . Carbon::now(),
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Pembatalan Pembayaran Berhasil Dikonfirmasi']);
    }
}
