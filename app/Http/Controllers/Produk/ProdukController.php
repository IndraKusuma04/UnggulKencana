<?php

namespace App\Http\Controllers\Produk;

use App\Models\Produk;
use App\Models\JenisProduk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProdukController extends Controller
{
    private function generateKodeProduk()
    {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomCode = '';

        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomCode;
    }

    public function getProduk()
    {
        $produk = Produk::where('status', 1)->with(['jenisproduk', 'kondisi'])->get();

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Ditemukan', 'Data' => $produk]);
    }

    public function storeProduk(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'integer'  => ':attribute format wajib menggunakan angka',
            'mimes'    => ':attribute format wajib menggunakan PNG/JPG'
        ];

        $credentials = $request->validate([
            'nama'          =>  'required',
            'jenis'         =>  'required|' . Rule::in(JenisProduk::where('status', 1)->pluck('id')),
            'harga_jual'    =>  'integer',
            'harga_beli'    =>  'integer',
            'keterangan'    =>  'string',
            'berat'         =>  [
                'required',
                'regex:/^\d+\.\d{1,}$/'
            ],
            'kondisi'       =>  'required',
            'karat'         =>  'required|integer',
            'lingkar'       =>  'integer',
            'panjang'       =>  'integer',
            'imageProduk'   =>  'nullable|mimes:png,jpg',
        ], $messages);

        $kodeproduk = $this->generateKodeProduk();

        $content = QrCode::format('png')->size(300)->generate($kodeproduk); // Ini menghasilkan data PNG sebagai string

        // Tentukan nama file
        $fileName = 'barcode/' . $kodeproduk . '.png';

        // Simpan file ke dalam storage/public/barcode/
        Storage::put($fileName, $content);

        if ($request->file('imageProduk')) {
            $extension = $request->file('imageProduk')->getClientOriginalExtension();
            $fileName = $kodeproduk . '.' . $extension;
            $request->file('imageProduk')->storeAs('produk', $fileName);
            $imageProduk = $request['imageProduk'] = $fileName;
        }

        $data = Produk::create([
            'kodeproduk'        =>  $kodeproduk,
            'nama'              =>  $request->nama,
            'jenisproduk_id'    =>  $request->jenis,
            'kondisi_id'        =>  $request->kondisi,
            'berat'             =>  $request->berat,
            'karat'             =>  $request->karat,
            'lingkar'           =>  $request->lingkar,
            'panjang'           =>  $request->panjang,
            'harga_jual'        =>  $request->hargajual,
            'harga_beli'        =>  $request->hargabeli,
            'keterangan'        =>  $request->keterangan,
            'image_produk'      =>  $imageProduk,
            'status'            =>  1,
        ]);

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Disimpan', 'Data' => $data]);
    }

    public function getProdukByID($id)
    {
        $produk = Produk::where('id', $id)->with(['jenisproduk', 'kondisi'])->get();

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Ditemukan', 'Data' => $produk]);
    }

    public function updateProduk(Request $request, $id)
    {
        $produk = Produk::where('id', $id)->first();

        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'integer'  => ':attribute format wajib menggunakan angka',
            'mimes'    => ':attribute format wajib menggunakan PNG/JPG'
        ];

        $credentials = $request->validate([
            'nama'          =>  'required',
            'jenis'         =>  'required|' . Rule::in(JenisProduk::where('status', 1)->pluck('id')),
            'harga_jual'    =>  'integer',
            'harga_beli'    =>  'integer',
            'keterangan'    =>  'string',
            'berat'         =>  [
                'required',
                'regex:/^\d+\.\d{1,}$/'
            ],
            'kondisi'       =>  'required',
            'karat'         =>  'required|integer',
            'lingkar'       =>  'integer',
            'panjang'       =>  'integer',
            'imageProduk'   =>  'nullable|mimes:png,jpg',
        ], $messages);

        if ($request->file('imageProduk')) {
            $pathavatar     = 'storage/produk/' . $produk->image_produk;

            if (File::exists($pathavatar)) {
                File::delete($pathavatar);
            }

            $extension = $request->file('imageProduk')->getClientOriginalExtension();
            $newImage = $produk->kodeproduk . '.' . $extension;
            $request->file('imageProduk')->storeAs('produk', $newImage);
            $request['imageProduk'] = $newImage;

            $updateProduk = Produk::where('id', $id)
                ->update([
                    'nama'              =>  $request->nama,
                    'jenisproduk_id'    =>  $request->jenis,
                    'kondisi_id'        =>  $request->kondisi,
                    'berat'             =>  $request->berat,
                    'karat'             =>  $request->karat,
                    'lingkar'           =>  $request->lingkar,
                    'panjang'           =>  $request->panjang,
                    'harga_jual'        =>  $request->hargajual,
                    'harga_beli'        =>  $request->hargabeli,
                    'keterangan'        =>  $request->keterangan,
                    'image_produk'      =>  $newImage,
                ]);
        } else {
            $updateProduk = Produk::where('id', $id)
                ->update([
                    'nama'              =>  $request->nama,
                    'jenisproduk_id'    =>  $request->jenis,
                    'kondisi_id'        =>  $request->kondisi,
                    'berat'             =>  $request->berat,
                    'karat'             =>  $request->karat,
                    'lingkar'           =>  $request->lingkar,
                    'panjang'           =>  $request->panjang,
                    'harga_jual'        =>  $request->hargajual,
                    'harga_beli'        =>  $request->hargabeli,
                    'keterangan'        =>  $request->keterangan,
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Disimpan', 'Data' => $produk]);
    }

    public function deleteProduk($id)
    {
        // Cari data produk berdasarkan ID
        $produk = Produk::find($id);

        // Periksa apakah data ditemukan
        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $produk->update([
            'status' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Produk Berhasil Dihapus.']);
    }
}
