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
}
