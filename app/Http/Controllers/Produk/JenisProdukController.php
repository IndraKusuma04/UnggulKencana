<?php

namespace App\Http\Controllers\Produk;

use App\Models\JenisProduk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

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

    public function updateJenisProduk(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'mimes'    => ':attribute format wajib menggunakan PNG/JPG',
            'unique'   => ':attribute sudah digunakan'
        ];

        $credentials = $request->validate([
            'jenisproduk'  => 'required',
            'imageProduk'  => 'mimes:png,jpg,jpeg',
        ], $messages);

        $jenisproduk = JenisProduk::where('id', $id)->first();

        if ($request->file('imagejenisproduk')) {
            $pathIcon     = 'storage/icon/' . $jenisproduk->image_jenis_produk;

            if (File::exists($pathIcon)) {
                File::delete($pathIcon);
            }

            $extension = $request->file('imagejenisproduk')->getClientOriginalExtension();
            $newImageJenisProduk = $request->jenisproduk . '.' . $extension;
            $request->file('imagejenisproduk')->storeAs('icon', $newImageJenisProduk);
            $request['imagejenisproduk'] = $newImageJenisProduk;

            JenisProduk::where('id', $id)
                ->update([
                    'jenis_produk'          => $request->jenisproduk,
                    'image_jenis_produk'    => $newImageJenisProduk,
                ]);
        } else {
            JenisProduk::where('id', $id)
                ->update([
                    'jenis_produk'          => $request->jenisproduk,
                ]);
        }
        return response()->json(['success' => true, 'message' => "Data Jenis Produk Berhasil Disimpan"]);
    }

    public function deleteJenisProduk($id)
    {
        // Cari data jenis produk berdasarkan ID
        $jenisproduk = JenisProduk::find($id);

        // Periksa apakah data ditemukan
        if (!$jenisproduk) {
            return response()->json(['success' => false, 'message' => 'Jenis Produk tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $jenisproduk->update([
            'status' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Jenis Produk Berhasil Dihapus.']);
    }
}
