<?php

namespace App\Http\Controllers\Produk;

use App\Http\Controllers\Controller;
use App\Models\JenisProduk;
use Illuminate\Http\Request;

class JenisProdukController extends Controller
{
    public function getJenisProduk()
    {
        $jenisproduk = JenisProduk::where('status', 1)->get();

        return response()->json(['success' => true, 'message' => 'Data Jenis Produk Berhasil Ditemukan', 'Data' => $jenisproduk]);
    }

    public function storeJenisProduk(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'mimes'    => ':attribute format wajib menggunakan PNG/JPG'
        ];

        $credentials = $request->validate([
            'jenisproduk'       => 'required',
            'imagejenisproduk'  => 'mimes:png,jpg,jpeg',
        ], $messages);

        $imagejenisproduk = "";
        if ($request->file('imagejenisproduk')) {
            $extension = $request->file('imagejenisproduk')->getClientOriginalExtension();
            $imagejenisproduk = $request->jenisproduk . '.' . $extension;
            $request->file('imagejenisproduk')->storeAs('icon', $imagejenisproduk);
            $request['imagejenisproduk'] = $imagejenisproduk;
        }

        $jenisproduk = JenisProduk::create([
            'jenis_produk'          => $request->jenisproduk,
            'image_jenis_produk'    => $imagejenisproduk,
            'status' => 1
        ]);

        return response()->json(['success' => true, 'message' => "Data Jenis Berhasil Disimpan", 'Data' => $jenisproduk]);
    }

    public function getJenisProdukByID($id)
    {
        $jenisproduk = JenisProduk::where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Data Jenis Produk Berhasil Ditemukan', 'Data' => $jenisproduk]);
    }
}
