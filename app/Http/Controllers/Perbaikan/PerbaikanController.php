<?php

namespace App\Http\Controllers\Perbaikan;

use Carbon\Carbon;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
}
