<?php

namespace App\Http\Controllers\Stok;

use App\Models\NampanProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\StokNampan;

class StokNampanController extends Controller
{
    public function getNampanStok()
    {
        $stokNampan = StokNampan::with(['nampan', 'nampan.jenisProduk'])->get();

        return response()->json(['success' => true, 'message' => 'Data Stok Berhasil Ditemukan', 'Data' => $stokNampan]);
    }

    public function detailNampanStok($id)
    {
        $stok = DB::table('stok_nampan as sn')
            ->leftJoin('nampan as n', 'sn.nampan_id', '=', 'n.id')
            ->leftJoin('jenis_produk as jp', 'n.jenisproduk_id', '=', 'jp.id')
            ->leftJoin('nampan_produk as np', 'sn.nampan_id', '=', 'np.nampan_id')
            ->leftJoin('produk as p', 'np.produk_id', '=', 'p.id')
            ->where('sn.nampan_id', $id)
            ->orderBy('np.jenis')
            ->orderBy('np.tanggalmasuk')
            ->select(
                'n.nampan',
                'jp.jenis_produk',
                'p.nama',
                'p.berat',
                'np.jenis',
                'np.tanggalmasuk',
                'np.tanggalkeluar',
                'sn.stokprodukawal',
                'sn.stokprodukakhir',
                'sn.stokawalberat',
                'sn.stokakhirberat'
            )
            ->get();

        return response()->json(['success' => true, 'message' => 'Detail Stok Berhasil Ditemukan', 'Data' => $stok]);
    }
}
