<?php

namespace App\Http\Controllers\Keranjang;

use App\Models\Produk;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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

    public function addToCart(Request $request)
    {
        //GENERATE CODE KERANJANG
        $generateCode = $this->generateKodeKeranjang();

        // Memanggil cekItem untuk memeriksa apakah item dengan status 1 sudah ada
        $existingItem = DB::table('keranjang')
            ->where('produk_id', $request->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($existingItem && $existingItem->status == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk sudah ada di keranjang'
            ]);
        }

        //HargaBarang
        $harga  = Produk::where('id', $request->id)->first()->harga_jual;
        $berat  = Produk::where('id', $request->id)->first()->berat;
        $total  = $harga * $berat;

        $request['kodekeranjang']   = $generateCode;
        $request['produk_id']       = $request->id;
        $request['berat']           = $request->berat;
        $request['karat']           = $request->karat;
        $request['harga_jual']      = $request->harga_jual;
        $request['lingkar']         = $request->lingkar;
        $request['panjang']         = $request->panjang;
        $request['total']           = $total;
        $request['oleh']            = Auth::user()->id;
        $request['status']          = 1;

        $keranjang = Keranjang::create($request->all());

        return response()->json(['success' => true, 'message' => 'Produk Berhasil Ditambahkan', 'data' => $keranjang]);
    }

    public function deleteKeranjangAll()
    {
        $keranjang = Keranjang::where('status', 1)
            ->where('oleh', Auth::user()->id)
            ->update([
                'status'    =>  0
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua Produk berhasil dibatalkan'
        ]);
    }

    public function deleteKeranjangByID($id)
    {
        $keranjang = Keranjang::where('id', $id)
            ->update([
                'status' => 0
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dibatalkan',
        ]);
    }

    public function getKodeKeranjang()
    {
        // Ambil data keranjang pertama dengan status 1 dan user_id pengguna yang sedang login
        $keranjang = Keranjang::where('status', 1)
            ->where('oleh', Auth::user()->id)
            ->first();

        // Cek apakah keranjang ditemukan
        if ($keranjang) {
            // Ambil kode keranjang
            $kodeKeranjang = $keranjang->kodekeranjang;

            // Kembalikan response JSON dengan kode keranjang dan produk ID
            return response()->json(['success' => true, 'kode' => $kodeKeranjang]);
        } else {
            // Jika keranjang tidak ditemukan
            return response()->json([
                'success' => false,
                'message' => 'Belum ada barang dalam keranjang'
            ]);
        }
    }
}
