<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function getPembelian()
    {
        $pembelian = Pembelian::with(['suplier', 'pelanggan', 'pembelianproduk'])->get();

        return response()->json(['success' => true, 'message' => 'Data Pembelian Berhasil Ditemukan', 'Data' => $pembelian]);
    }
}
