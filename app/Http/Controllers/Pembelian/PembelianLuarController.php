<?php

namespace App\Http\Controllers\Pembelian;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\JenisProduk;
use App\Models\Kondisi;
use App\Models\PembelianProduk;

class PembelianLuarController extends Controller
{
    public function storePembelianProduk(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'integer'  => ':attribute format wajib menggunakan angka',
            'mimes'    => ':attribute format wajib menggunakan PNG/JPG'
        ];

        $credentials = $request->validate([
            'nama'                  =>  'required',
            'jenisproduk_id'        =>  'required|' . Rule::in(JenisProduk::where('status', 1)->pluck('id')),
            'kondisi_id'            =>  'required|' . Rule::in(Kondisi::where('status', 1)->pluck('id')),
            'berat'                 =>  [
                'required',
                'regex:/^\d+\.\d{1,}$/'
            ],
            'karat'                 =>  'required|integer',
            'lingkar'               =>  'required|integer',
            'panjang'               =>  'required|integer',
            'harga_beli'             => 'required|integer',
        ], $messages);

        $pembeliantokocontroller = new PembelianTokoController();
        $kode = $pembeliantokocontroller->generateKodePembelianProduk();

        // PembelianProduk::create([
        //     ''
        // ]);
    }
}
