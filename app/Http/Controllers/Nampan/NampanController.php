<?php

namespace App\Http\Controllers\Nampan;

use App\Http\Controllers\Controller;
use App\Models\Nampan;
use App\Models\NampanProduk;
use App\Models\StokNampan;
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

        if ($nampan->status_final == 2) {
            return response()->json(['success' => false, 'message' => 'Nampan sudah difinal sebelumnya.']);
        }

        // Ambil tanggal pembuatan nampan (misal kolom created_at atau tanggal lainnya)
        $tanggalNampan = $nampan->tanggal;

        // Hitung stok produk awal (jumlah produk_id) dan berat total produk awal
        $stokProdukAwal = NampanProduk::where('nampan_id', $id)
            ->whereDate('tanggalmasuk', $tanggalNampan)
            ->where('jenis', 'awal')
            ->where('status', 1)
            ->count();

        $stokBeratAwal = NampanProduk::where('nampan_produk.nampan_id', $id)
            ->whereDate('nampan_produk.tanggalmasuk', $tanggalNampan)
            ->where('nampan_produk.jenis', 'awal')
            ->where('nampan_produk.status', 1)
            ->join('produk', 'nampan_produk.produk_id', '=', 'produk.id')
            ->sum('produk.berat');


        // Update status final dan bisa simpan stok awal ke kolom nampan jika ada
        $nampan->update([
            'status_final' => 2,
        ]);

        StokNampan::create([
            'nampan_id'         =>  $id,
            'tanggal'           =>  $tanggalNampan,
            'stokprodukawal'    =>  $stokProdukAwal,
            'stokawalberat'     =>  $stokBeratAwal,
            'status'            =>  1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nampan berhasil difinalkan.'
        ]);
    }

    public function tutupNampan($id)
    {
        $nampan = Nampan::findOrFail($id);
        $tanggalNampan = $nampan->tanggal;

        $stok = StokNampan::where('nampan_id', $id)
            ->whereDate('tanggal', $tanggalNampan)
            ->first();

        if (!$stok) {
            return response()->json(['success' => false, 'message' => 'Nampan belum difinal untuk awal hari.']);
        }

        // Hitung produk masuk
        $jumlahMasuk = NampanProduk::where('nampan_id', $id)
            ->where('jenis', 'masuk')
            ->whereDate('tanggalmasuk', $tanggalNampan)
            ->where('status', 1)
            ->count();

        $beratMasuk = NampanProduk::where('nampan_produk.nampan_id', $id)
            ->where('nampan_produk.jenis', 'masuk')
            ->whereDate('nampan_produk.tanggalmasuk', $tanggalNampan)
            ->where('nampan_produk.status', 1)
            ->join('produk', 'nampan_produk.produk_id', '=', 'produk.id')
            ->sum('produk.berat');

        // Hitung produk keluar
        $jumlahKeluar = NampanProduk::where('nampan_id', $id)
            ->where('jenis', 'keluar')
            ->whereDate('tanggalmasuk', $tanggalNampan)
            ->where('status', 1)
            ->count();

        $beratKeluar = NampanProduk::where('nampan_produk.nampan_id', $id)
            ->where('nampan_produk.jenis', 'keluar')
            ->whereDate('nampan_produk.tanggalmasuk', $tanggalNampan)
            ->where('nampan_produk.status', 1)
            ->join('produk', 'nampan_produk.produk_id', '=', 'produk.id')
            ->sum('produk.berat');

        // Hitung stok akhir
        $stokProdukAkhir = $stok->stokprodukawal + $jumlahMasuk - $jumlahKeluar;
        $stokBeratAkhir = $stok->stokawalberat + $beratMasuk - $beratKeluar;

        // Update stok akhir
        $stok->update([
            'stokprodukakhir' => $stokProdukAkhir,
            'stokakhirberat' => $stokBeratAkhir,
        ]);

        // Update status final di tabel nampan jadi 2 (tutup)
        $nampan->update(['status' => 2]);

        return response()->json(['success' => true, 'message' => 'Nampan berhasil ditutup dan stok akhir dihitung.']);
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
