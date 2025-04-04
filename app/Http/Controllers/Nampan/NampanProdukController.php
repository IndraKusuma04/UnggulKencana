<?php

namespace App\Http\Controllers\Nampan;

use Carbon\Carbon;
use App\Models\Nampan;
use App\Models\Produk;
use App\Models\NampanProduk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NampanProdukController extends Controller
{
    public function getNampanProduk($id)
    {
        $nampanProduk = NampanProduk::with(['nampan', 'produk', 'user'])->where('nampan_id', $id)->where('status', 1)->get();
        return response()->json(['success' => true, 'message' => 'Data Nampan Produk Berhasil Ditemukan', 'Data' => $nampanProduk]);
    }

    public function getProdukNampan($id)
    {
        $nampan = Nampan::where('id', $id)->first();

        if (!$nampan) {
            return response()->json(['success' => false, 'message' => 'Data Nampan Tidak Ditemukan'], 404);
        }

        $produk = Produk::with('jenisproduk')->where('jenisproduk_id', $nampan->jenisproduk_id)->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Nampan Produk Berhasil Ditemukan',
            'Data' => $produk
        ]);
    }

    public function storeProdukNampan(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        // Ambil daftar produk_id yang sudah ada di NampanProduk
        $existingProducts = NampanProduk::whereIn('produk_id', $request->items)
            ->pluck('produk_id')
            ->toArray();

        if (!empty($existingProducts)) {
            return response()->json(['success' => false, 'message' => 'Beberapa produk sudah ada.']);
        }

        // Tambahkan produk yang belum ada
        $nampanProducts = [];
        foreach ($request->items as $item) {
            $nampanProducts[] = NampanProduk::create([
                'nampan_id' => $id,
                'produk_id' => $item,
                'tanggal'   => Carbon::today()->format('Y-m-d'),
                'oleh'      => Auth::user()->id,
                'status'    => 1,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan']);
    }

    public function deleteNampanProduk($id)
    {
        // Cari data produk berdasarkan ID
        $nampanProduk = NampanProduk::find($id);

        // Periksa apakah data ditemukan
        if (!$nampanProduk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $nampanProduk->update([
            'status' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Produk Berhasil Dihapus.']);
    }
}
