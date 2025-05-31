<?php

namespace App\Http\Controllers\Stok;

use App\Models\Nampan;
use App\Models\StokNampan;
use App\Models\NampanProduk;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StokNampanController extends Controller
{
    public function getNampanStok()
    {
        $stokNampan = StokNampan::with(['nampan', 'nampan.jenisProduk'])->get();

        return response()->json(['success' => true, 'message' => 'Data Stok Berhasil Ditemukan', 'Data' => $stokNampan]);
    }

    public function detailNampanStok($id)
    {
        $nampan_id = StokNampan::where('id', $id)->first()->nampan_id;

        $nampan = Nampan::with([
            'jenisProduk:id,jenis_produk',
            'stokNampan:id,nampan_id,tanggal,stokprodukawal,stokawalberat,stokprodukakhir,stokakhirberat',
            'nampanProduk' => function ($query) {
                $query->with(['produk:id,kodeproduk,nama,berat'])
                    ->orderBy('tanggalmasuk')
                    ->orderBy('tanggalkeluar');
            }
        ])->findOrFail($nampan_id); // Ganti dengan $nampan_id dinamis

        return response()->json(['success' => true, 'message' => 'Data Detail Stok Berhasil Ditemukan', 'Data' => $nampan]);
    }
}
