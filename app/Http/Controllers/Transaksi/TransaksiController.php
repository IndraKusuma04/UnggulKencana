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
        // Ambil kode customer terakhir dari database
        $lastCustomer = DB::table('transaksi')
            ->orderBy('kodetransaksi', 'desc')
            ->first();

        // Jika tidak ada customer, mulai dari 1
        $lastNumber = $lastCustomer ? (int) substr($lastCustomer->kodetransaksi, -5) : 0;

        // Tambahkan 1 pada nomor terakhir
        $newNumber = $lastNumber + 1;

        // Format kode customer baru
        $newKodeCustomer = Carbon::now()->format('YmdHis') . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return $newKodeCustomer;
    }

    public function getKodeTransaksi()
    {
        $kodetransaksi = $this->generateKodeTransaksi();
        return response()->json(['success' => true, 'kodetransaksi' => $kodetransaksi]);
    }

    public function payment(Request $request)
    {
        // Ambil semua produk_id dari keranjang aktif user tersebut
        $produkIDs = Keranjang::where('status', 1)
            ->where('oleh', Auth::id())
            ->where('kodekeranjang', $request->kodeKeranjangID)
            ->pluck('produk_id');

        // Buat transaksi baru
        $payment = Transaksi::create([
            'kodetransaksi'     => $request->transaksiID,
            'kodekeranjang_id'  => $request->kodeKeranjangID,
            'pelanggan_id'      => $request->pelangganID,
            'diskon_id'         => $request->diskonID,
            'tanggal'           => Carbon::today()->format('Y-m-d'),
            'total'             => $request->total,
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
                    ->update(['status' => 2]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi Berhasil',
                'transaksi_id' => $payment->id, // kalau nanti mau dipakai buat cetak
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
        $transaksi  = Transaksi::where('id', $id)
            ->update([
                'status' => 0,
            ]);

        return response()->json(['success' => true, 'message' => 'Pembatalan Pembayaran Berhasil Dikonfirmasi']);
    }

    public function getTransaksiByID($id)
    {
        $transaksi = Transaksi::with(['keranjang', 'keranjang.produk', 'pelanggan', 'user', 'user.pegawai', 'diskon'])->where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Transaksi Berhasil Ditemukan', 'Data' => $transaksi]);
    }
}
