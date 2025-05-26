<?php

namespace App\Http\Controllers\Perbaikan;

use Carbon\Carbon;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Produk;

class PerbaikanController extends Controller
{
    public function kodePerbaikan()
    {
        $now = Carbon::now()->format('YmdHis');

        // Random 3 digit (untuk menghindari duplikasi pada timestamp yang sama)
        $random = rand(100, 999);

        // Ambil urutan terakhir jika ingin tetap menyimpan pola urutan
        $last = DB::table('perbaikan')
            ->orderBy('id', 'desc')
            ->first();

        $lastNumber = $last ? $last->id + 1 : 1;

        // Format akhir kode
        $kode = 'PBK-' . $now . '-' . $random . '-' . str_pad($lastNumber, 4, '0', STR_PAD_LEFT);

        return $kode;
    }

    public function getPerbaikan()
    {
        $perbaikan = Perbaikan::with(['produk', 'kondisi'])->get();

        return response()->json(['success' => true, 'message' => 'Data Perbaikan Berhasil Ditemukan', 'Data' => $perbaikan]);
    }

    public function getPerbaikanByID($id)
    {
        $perbaikan = Perbaikan::where('id', $id)->with(['produk', 'kondisi'])->get();

        return response()->json(['success' => true, 'message' => 'Data Perbaikan Berhasil Ditemukan', 'Data' => $perbaikan]);
    }

    public function konfirmasiPerbaikan($id)
    {

        $produk_id = Perbaikan::where('id', $id)->first()->produk_id;
        $perbaikan  = Perbaikan::where('id', $id)
            ->update([
                'status'        => 2,
                'kondisi_id'    => 1,
                'keterangan'    => "Produk Berhasil Diperbaiki, Tanggal Keluar " . Carbon::now(),
            ]);

        if ($perbaikan) {
            $produkIDs = Produk::where('id', $produk_id)
                ->update([
                    'status'        => 1,
                    'kondisi_id'    => 1
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Pembayaran Berhasil Dikonfirmasi']);
    }
}
