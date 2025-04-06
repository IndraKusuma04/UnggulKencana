<?php

namespace App\Http\Controllers\Keranjang;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    private function generateKodeKeranjang()
    {
        // Cek apakah ada kode keranjang terakhir dengan status 1
        $lastKeranjangWithStatusOne = DB::table('keranjang')
            ->where('status', 1)
            ->orderBy('kodekeranjang', 'desc')
            ->first();

        // Jika ada kode keranjang dengan status 1, gunakan kode itu
        if ($lastKeranjangWithStatusOne) {
            return $lastKeranjangWithStatusOne->kodekeranjang;
        }

        // Jika tidak ada keranjang dengan status 1, ambil kode keranjang terakhir secara umum
        $lastKeranjang = DB::table('keranjang')
            ->orderBy('kodekeranjang', 'desc')
            ->first();

        // Jika tidak ada keranjang sama sekali, mulai dari 1
        $lastNumber = $lastKeranjang ? (int) substr($lastKeranjang->kodekeranjang, -5) : 0;

        // Tambahkan 1 pada nomor terakhir
        $newNumber = $lastNumber + 1;

        // Format kode keranjang baru
        $newKodeKeranjang = '#K-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return $newKodeKeranjang;
    }

    public function getKeranjang()
    {
        $keranjang = Keranjang::where('status', 1)
            ->where('oleh', Auth::user()->id)
            ->with(['produk', 'user'])
            ->get();

        $count = $keranjang->count();

        $totalKeranjang = Keranjang::where('status', 1)
            ->where('oleh', Auth::id())
            ->sum('total');

        return response()->json(['success' => true, 'message' => 'Data Keranjang Berhasil Ditemukan', 'Data' => $keranjang, 'TotalKeranjang' => $count, 'TotalHargaKeranjang' => $totalKeranjang]);
    }
}
