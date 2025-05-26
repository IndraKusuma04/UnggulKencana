<?php

namespace App\Http\Controllers\Transaksi;

use Carbon\Carbon;
use App\Models\Produk;
use App\Models\Keranjang;
use App\Models\Transaksi;
use App\Models\NampanProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    private function generateKodeTransaksi()
    {
        $now = Carbon::now()->format('YmdHis');

        // Random 3 digit (untuk menghindari duplikasi pada timestamp yang sama)
        $random = rand(100, 999);

        // Ambil urutan terakhir jika ingin tetap menyimpan pola urutan
        $last = DB::table('transaksi')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = $last ? $last->id + 1 : 1;

        // Format akhir kode
        $kode = 'TRK-' . $now . '-' . $random . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        return $kode;
    }

    public function getKodeTransaksi()
    {
        $kodetransaksi = $this->generateKodeTransaksi();
        return response()->json(['success' => true, 'kodetransaksi' => $kodetransaksi]);
    }

    private function terbilang($angka)
    {
        $angka = abs($angka);
        $huruf = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

        if ($angka < 12) {
            return $huruf[$angka];
        } elseif ($angka < 20) {
            return $this->terbilang($angka - 10) . " belas";
        } elseif ($angka < 100) {
            return $this->terbilang(floor($angka / 10)) . " puluh " . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            return "seratus " . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return $this->terbilang(floor($angka / 100)) . " ratus " . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return "seribu " . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            return $this->terbilang(floor($angka / 1000)) . " ribu " . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            return $this->terbilang(floor($angka / 1000000)) . " juta " . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            return $this->terbilang(floor($angka / 1000000000)) . " miliar " . $this->terbilang($angka % 1000000000);
        } else {
            return "angka terlalu besar";
        }
    }

    public function payment(Request $request)
    {
        // Ambil semua produk_id dari keranjang aktif user tersebut
        $produkIDs = Keranjang::where('status', 1)
            ->where('oleh', Auth::id())
            ->where('kodekeranjang', $request->kodeKeranjangID)
            ->pluck('produk_id');

        $angka = abs($request->total);
        $terbilang = ucwords(trim($this->terbilang($angka))) . ' Rupiah';

        // Buat transaksi baru
        $payment = Transaksi::create([
            'kodetransaksi'     => $request->transaksiID,
            'kodekeranjang_id'  => $request->kodeKeranjangID,
            'pelanggan_id'      => $request->pelangganID,
            'diskon_id'         => $request->diskonID,
            'tanggal'           => Carbon::today()->format('Y-m-d'),
            'total'             => $request->total,
            'terbilang'         => $terbilang,
            'oleh'              => Auth::id(),
            'status'            => 1,
        ]);

        if ($payment) {
            // Update status keranjang jadi 2
            Keranjang::where('status', 1)
                ->where('oleh', Auth::id())
                ->where('kodekeranjang', $request->kodeKeranjangID)
                ->update([
                    'status' => 2,
                ]);

            // Update status semua produk dan relasi ke nampan
            foreach ($produkIDs as $produk_id) {
                Produk::where('id', $produk_id)
                    ->update(['status' => 2]);

                NampanProduk::where('produk_id', $produk_id)
                    ->update([
                        'status' => 2,
                        'tanggalkeluar' => Carbon::now()
                    ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi Berhasil',
                'transaksi_id' => $payment->id,
                'terbilang' => $terbilang,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Transaksi gagal disimpan.',
        ]);
    }

    public function getTransaksi()
    {
        $transaksi = Transaksi::with(['keranjang', 'keranjang.produk', 'pelanggan'])->get();

        return response()->json(['success' => true, 'message' => 'Data Transaksi Berhasil Ditemukan', 'Data' => $transaksi]);
    }

    public function konfirmasiPembayaran($id)
    {
        $transaksi  = Transaksi::where('id', $id)
            ->update([
                'status' => 2,
            ]);

        return response()->json(['success' => true, 'message' => 'Pembayaran Berhasil Dikonfirmasi']);
    }

    public function konfirmasiPembatalanPembayaran($id)
    {
        $kodekeranjang = Transaksi::where('id', $id)->first()->kodekeranjang_id;

        $produkIds = Keranjang::where('kodekeranjang', $kodekeranjang)
            ->pluck('produk_id')
            ->toArray();

        $transaksi  = Transaksi::where('id', $id)
            ->update([
                'status' => 0,
            ]);

        // Jika berhasil update transaksi, ubah status produk di nampan_produk
        if ($transaksi) {
            NampanProduk::whereIn('produk_id', $produkIds)
                ->update(['status' => 1]); // aktif kembali

            Produk::whereIn('id', $produkIds)
                ->update(['status' => 1]);
        }

        return response()->json(['success' => true, 'message' => 'Pembatalan Pembayaran Berhasil Dikonfirmasi']);
    }

    public function getTransaksiByID($id)
    {
        $transaksi = Transaksi::with(['keranjang' => function ($query) {
            $query->where('status', '!=', 0);
        }, 'keranjang.produk', 'pelanggan', 'user', 'user.pegawai', 'diskon'])->where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Transaksi Berhasil Ditemukan', 'Data' => $transaksi]);
    }
}
