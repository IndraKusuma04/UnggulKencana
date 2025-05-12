<?php

namespace App\Http\Controllers\Pembelian;

use App\Models\Kondisi;
use App\Models\JenisProduk;
use Illuminate\Http\Request;
use App\Models\PembelianProduk;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Produk\ProdukController;
use App\Models\Produk;

class PembelianLuarController extends Controller
{
    public function getPembelianProduk()
    {
        $pembelian = PembelianProduk::with(['jenisproduk', 'produk', 'kondisi'])
            ->where('status', 1)
            ->where('oleh', Auth::user()->id)
            ->where('jenispembelian', 2)
            ->get();

        return response()->json(['success' => true, 'message' => 'Data Pembelian Berhasil Ditemukan', 'Data' => $pembelian]);
    }

    public function storePembelianProduk(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'integer'  => ':attribute format wajib menggunakan angka',
            'mimes'    => ':attribute format wajib menggunakan PNG/JPG'
        ];

        $credentials = $request->validate([
            'nama'                  =>  'required',
            'jenis'        =>  'required|' . Rule::in(JenisProduk::where('status', 1)->pluck('id')),
            'kondisi'            =>  'required|' . Rule::in(Kondisi::where('status', 1)->pluck('id')),
            'berat'                 =>  [
                'required',
                'regex:/^\d+\.\d{1,}$/'
            ],
            'karat'                 =>  'required|integer',
            'lingkar'               =>  'required|integer',
            'panjang'               =>  'required|integer',
            'hargabeli'             => 'required|integer',
        ], $messages);

        $pembeliantokocontroller    = new PembelianTokoController();
        $kode                       = $pembeliantokocontroller->generateKodePembelianProduk();

        $kodeproduk                 = new ProdukController();
        $newkodeproduk              = $kodeproduk->generateKodeProduk();

        $content = QrCode::format('png')->size(300)->margin(5)->generate($newkodeproduk); // Ini menghasilkan data PNG sebagai string

        // Tentukan nama file
        $fileName = 'barcode/' . $newkodeproduk . '.png';

        // Simpan file ke dalam storage/public/barcode/
        Storage::put($fileName, $content);

        // Cek apakah sudah ada kodepembelianproduk di session
        $kodepembelianproduk = session('kodepembelianproduk');

        if (!$kodepembelianproduk) {
            $kodepembelianproduk = $kode;
            session(['kodepembelianproduk' => $kodepembelianproduk]);
        }

        $createProduk = Produk::create([
            'kodeproduk'        =>  $newkodeproduk,
            'jenisproduk_id'    =>  $request->jenis,
            'nama'              =>  $request->nama,
            'harga_jual'        =>  0,
            'harga_beli'        =>  $request->hargabeli,
            'berat'             =>  $request->berat,
            'karat'             =>  $request->karat,
            'lingkar'           =>  $request->lingkar,
            'panjang'           =>  $request->panjang,
            'keterangan'        =>  $request->keterangan,
            'kondisi_id'        =>  $request->kondisi,
            'status'            =>  0,
        ]);

        if ($createProduk) {
            $pembelianproduk = PembelianProduk::create([
                'kodepembelianproduk'   =>  $kode,
                'kodeproduk'            =>  $newkodeproduk,
                'jenisproduk_id'        =>  $request->jenis,
                'nama'                  =>  $request->nama,
                'harga_beli'            =>  $request->hargabeli,
                'berat'                 =>  $request->berat,
                'karat'                 =>  $request->karat,
                'lingkar'               =>  $request->lingkar,
                'panjang'               =>  $request->panjang,
                'keterangan'            =>  $request->keterangan,
                'kondisi_id'            =>  $request->kondisi,
                'oleh'                  =>  Auth::user()->id,
                'jenispembelian'        =>  2,
                'status'                =>  1,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Data Berhasil Disimpan', 'kode' => $kodepembelianproduk]);
    }

    public function getPembelianByID($id)
    {
        // Cari data pelanggan berdasarkan ID
        $produk = PembelianProduk::find($id);

        // Periksa apakah data ditemukan
        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Produk Berhasil Dibatalkan.', 'Data' => $produk]);
    }
}
