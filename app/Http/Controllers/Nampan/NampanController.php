<?php

namespace App\Http\Controllers\Nampan;

use App\Http\Controllers\Controller;
use App\Models\Nampan;
use App\Models\NampanProduk;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NampanController extends Controller
{
    public function getNampan()
    {
        $nampan = Nampan::where('status', 1)
            ->with(['jenisProduk'])
            ->withCount([
                'produk' => function ($query) {
                    $query->where('status', 1); // hanya hitung produk dengan status = 1
                }
            ])
            ->get();

        $totalProdukAll = NampanProduk::where('status', 1)->count();

        return response()->json([
            'success' => true,
            'message' => 'Data Nampan Berhasil Ditemukan',
            'Total' => $totalProdukAll,
            'Data' => $nampan
        ]);
    }

    public function storeNampan(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
        ];

        $credentials = $request->validate([
            'jenis'         => 'required',
            'nampan'        => 'required',
        ], $messages);

        $storeNampan = Nampan::create([
            'jenisproduk_id'  =>  $request->jenis,
            'nampan'          =>  $request->nampan,
            'tanggal'         =>  Carbon::now(),
            'status'          =>  1,
            'status_final'    =>  1,
        ]);

        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Disimpan']);
    }

    public function getNampanByID($id)
    {
        $nampan = Nampan::where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Ditemukan', 'Data' => $nampan]);
    }

    public function updateNampan(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
        ];

        $credentials = $request->validate([
            'jenis'         => 'required',
            'nampan'        => 'required',
        ], $messages);

        // Cari data nampan berdasarkan ID
        $nampan = Nampan::where('id', $id)->first();

        // Periksa apakah data ditemukan
        if (!$nampan) {
            return response()->json(['success' => false, 'message' => 'Nampan tidak ditemukan.'], 404);
        }

        // Update data nampan
        $nampan->update([
            'nampan'            =>  $request->nampan,
            'jenisproduk_id'    =>  $request->jenis
        ]);

        return response()->json(['success' => true, 'message' => 'Nampan Berhasil Diperbarui.']);
    }

    public function finalNampan($id)
    {
        $nampan = Nampan::findOrFail($id);
        $produkAwal  = NampanProduk::where('nampan_id', $id)->where('jenis', 'awal');
        $produkMasuk = NampanProduk::where('nampan_id', $id)->where('jenis', 'masuk');
        $produkKeluar = NampanProduk::where('nampan_id', $id)->where('jenis', 'keluar');

        $stokAkhirProduk = ($produkAwal->sum('stokprodukawal') + $produkMasuk->sum('stokprodukawal')) - $produkKeluar->sum('stokprodukawal');
        $stokAkhirBerat  = ($produkAwal->sum('stokawalberat') + $produkMasuk->sum('stokawalberat')) - $produkKeluar->sum('stokawalberat');

        $nampan->update([
            'status_final' => 2,
            // kalau mau, bisa juga simpan hasil finalnya di kolom baru seperti stokakhir
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nampan berhasil difinalkan.',
            'stok_produk_akhir' => $stokAkhirProduk,
            'stok_berat_akhir' => $stokAkhirBerat,
        ]);

        return response()->json(['success' => true, 'message' => 'Nampan Berhasil Difinal.']);
    }

    public function deleteNampan($id)
    {
        // Cari data nampan berdasarkan ID
        $nampan = Nampan::find($id);

        // Periksa apakah data ditemukan
        if (!$nampan) {
            return response()->json(['success' => false, 'message' => 'Nampan tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $nampan->update([
            'status' => 0,
        ]);

        if ($nampan) {
            NampanProduk::where('nampan_id', $id)->update([
                'status'    => 0,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Nampan Berhasil Dihapus.']);
    }
}
