<?php

namespace App\Http\Controllers\Stok;

use App\Models\NampanProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StokNampanController extends Controller
{
    public function getNampanStok()
    {
        $nampan = DB::table('nampan_produk as np')
            ->join('nampan as n', 'np.nampan_id', '=', 'n.id')
            ->join('jenis_produk as jp', 'n.jenisproduk_id', '=', 'jp.id')
            ->select('n.nampan', 'jp.jenis_produk', 'n.status', 'np.nampan_id', 'n.nampan', DB::raw('COUNT(np.produk_id) as totalProduk'))
            ->groupBy('np.nampan_id', 'n.nampan')
            ->get();

        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Ditemukan', 'Data' => $nampan]);
    }
}
