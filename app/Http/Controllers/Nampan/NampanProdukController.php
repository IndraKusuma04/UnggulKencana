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
        $query = NampanProduk::with(['nampan', 'produk', 'user'])->where('status', 1);

        if ($id !== 'all') {
            $query->where('nampan_id', $id);
        }

        $nampanProduk = $query->get();

        // Tambahkan hargatotal ke setiap produk
        $nampanProduk->each(function ($item) {
            if ($item->produk) {
                $item->produk->hargatotal = number_format(
                    (float) $item->produk->harga_jual * (float) $item->produk->berat,
                    2,
                    '.',
                    ''
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Data Nampan Produk Berhasil Ditemukan',
            'Data' => $nampanProduk
        ]);
    }

    public function getProdukNampan($id)
    {
        $nampan = Nampan::where('id', $id)->first();

        if (!$nampan) {
            return response()->json(['success' => false, 'message' => 'Data Nampan Tidak Ditemukan'], 404);
        }

        $produk = Produk::with('jenisproduk')->where('jenisproduk_id', $nampan->jenisproduk_id)->where('status', 1)->get();

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
            'jenis' => 'required|in:awal,masuk,keluar',
        ]);

        $jenis = $request->jenis;

        $nampan = Nampan::findOrFail($id);

        // Validasi jenis input berdasarkan status final nampan
        if ($nampan->status_final == 1 && $jenis != 'awal') {
            return response()->json([
                'success' => false,
                'message' => 'Nampan belum difinal, hanya boleh input produk dengan jenis "awal".'
            ]);
        }

        if ($nampan->status_final == 2 && $jenis != 'masuk') {
            return response()->json([
                'success' => false,
                'message' => 'Nampan sudah difinal, hanya boleh input produk dengan jenis "masuk".'
            ]);
        }

        // Cek produk yang sudah ada aktif (status=1) di nampan ini dan jenis 'awal' atau 'masuk'
        $existingProducts = NampanProduk::where('status', 1)
            ->where('nampan_id', $id)
            ->whereIn('produk_id', $request->items)
            ->pluck('produk_id')
            ->toArray();

        if (!empty($existingProducts)) {
            return response()->json(['success' => false, 'message' => 'Beberapa produk sudah ada.']);
        }

        $nampanProducts = [];

        foreach ($request->items as $produk_id) {
            // Ambil stokakhirproduk & berat dari record terakhir produk di nampan ini
            $nampanProducts[] = NampanProduk::create([
                'nampan_id'       => $id,
                'produk_id'       => $produk_id,
                'jenis'           => $jenis,
                'tanggalmasuk'    => Carbon::today()->format('Y-m-d'),
                'status'          => 1,
                'oleh'            => Auth::user()->id,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan']);
    }

    // Contoh fungsi getBeratProduk (bisa sesuaikan dengan model produkmu)
    private function getBeratProduk($produk_id)
    {
        $produk = Produk::find($produk_id);
        return $produk ? $produk->berat : 0;
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
