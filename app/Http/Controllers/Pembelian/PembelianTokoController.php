<?php

namespace App\Http\Controllers\Pembelian;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PembelianTokoController extends Controller
{
    public function getTransaksiByKodeTransaksi(Request $request)
    {

        if ($request->kodetransaksi) {
            $messages = [
                'required' => ':attribute wajib di isi !!!',
            ];

            $credentials = $request->validate([
                'kodetransaksi'       => 'required',
            ], $messages);

            $transaksi = Transaksi::with(['keranjang' => function ($query) {
                $query->where('status', '!=', 0);
            }, 'keranjang.produk', 'pelanggan', 'user', 'user.pegawai', 'diskon'])->where('kodetransaksi', $request->kodetransaksi)->where('status', 2)->get();

            // Cek apakah data transaksi ditemukan
            if ($transaksi->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan',
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Transaksi Berhasil Ditemukan', 'Data' => $transaksi]);
        } else {
            return response()->json(['success' => false, 'message' => 'Transaksi Belum Dicari']);
        }
    }
}
