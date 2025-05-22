<?php

namespace App\Http\Controllers\Pembelian;

use Carbon\Carbon;
use App\Models\Produk;
use App\Models\Kondisi;
use App\Models\Pembelian;
use App\Models\Perbaikan;
use App\Models\JenisProduk;
use Illuminate\Http\Request;
use App\Models\PembelianProduk;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Perbaikan\PerbaikanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Produk\ProdukController;

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

            if (in_array($request->kondisi, [2, 3])) {
                $perbaikanController = new PerbaikanController(); // Panggil controller asal
                $kodePerbaikan = $perbaikanController->kodePerbaikan(); // Dapatkan kode unik

                Perbaikan::create([
                    'kodeperbaikan' => $kodePerbaikan, // Tambahkan ke sini
                    'produk_id'     => $createProduk->id,
                    'kondisi_id'    => $request->kondisi,
                    'tanggalmasuk'  => now(),
                    'status'        => 1,
                    'keterangan'    => 'Masuk Tanggal ' . Carbon::now()->format('Y-m-d H:i:s'),
                    'oleh'          => Auth::user()->id,
                ]);
            }
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

    public function updatePembelianByID(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'integer'  => ':attribute format wajib menggunakan angka',
            'mimes'    => ':attribute format wajib menggunakan PNG/JPG'
        ];

        $credentials = $request->validate([
            'nama'                  =>  'required',
            'jenis'                 =>  'required|' . Rule::in(JenisProduk::where('status', 1)->pluck('id')),
            'kondisi'               =>  'required|' . Rule::in(Kondisi::where('status', 1)->pluck('id')),
            'berat'                 =>  [
                'required',
                'regex:/^\d+\.\d{1,}$/'
            ],
            'karat'                 =>  'required|integer',
            'lingkar'               =>  'required|integer',
            'panjang'               =>  'required|integer',
            'hargabeli'             => 'required|integer',
        ], $messages);

        $kodeproduk = PembelianProduk::where('id', $id)->first()->kodeproduk;
        $idproduk   = Produk::where('kodeproduk', $kodeproduk)->first()->id;

        $updateProduk = Produk::where('kodeproduk', $kodeproduk)
            ->update([
                'jenisproduk_id'    =>  $request->jenis,
                'nama'              =>  $request->nama,
                'harga_beli'        =>  $request->hargabeli,
                'berat'             =>  $request->berat,
                'karat'             =>  $request->karat,
                'lingkar'           =>  $request->lingkar,
                'panjang'           =>  $request->panjang,
                'keterangan'        =>  $request->keterangan,
                'kondisi_id'        =>  $request->kondisi
            ]);

        if ($updateProduk) {
            PembelianProduk::where('kodeproduk', $kodeproduk)
                ->where('status', 1)
                ->update([
                    'jenisproduk_id'    =>  $request->jenis,
                    'nama'              =>  $request->nama,
                    'harga_beli'        =>  $request->hargabeli,
                    'berat'             =>  $request->berat,
                    'karat'             =>  $request->karat,
                    'lingkar'           =>  $request->lingkar,
                    'panjang'           =>  $request->panjang,
                    'keterangan'        =>  $request->keterangan,
                    'kondisi_id'        =>  $request->kondisi
                ]);

            if (in_array($request->kondisi, [2, 3])) {
                // Cek apakah sudah ada data perbaikan
                $perbaikan = Perbaikan::where('produk_id', $idproduk)->first();

                if ($perbaikan) {
                    // Update jika sudah ada
                    $perbaikan->update([
                        'kondisi_id'    => $request->kondisi,
                        'status'        => 1, // aktif di perbaikan
                        'keterangan'    => 'Update Tanggal ' . now(),
                        'tanggalmasuk'  => now(),
                    ]);
                }
            } elseif ($request->kondisi == 1) {
                // Jika diubah menjadi kondisi "baik", set perbaikan menjadi tidak aktif
                Perbaikan::where('produk_id', $idproduk)->update([
                    'kondisi_id'    => 1,
                    'status'        => 0,
                    'keterangan'    => 'Selesai Tanggal ' . now(),
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Diupdate']);
    }

    public function deletePembelianProduk($id)
    {
        // Cari data pelanggan berdasarkan ID
        $produk = PembelianProduk::find($id);
        $kodeproduk = PembelianProduk::where('id', $id)->first()->kodeproduk;
        $idproduk   = Produk::where('kodeproduk', $kodeproduk)->first()->id;

        // Periksa apakah data ditemukan
        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $delete = $produk->update([
            'status' => 0,
        ]);

        if ($delete) {
            Produk::where('kodeproduk', $kodeproduk)->update(['status' => 0]);

            Perbaikan::where('produk_id', $idproduk)->update([
                'status'        => 0,
                'keterangan'    => 'Batal Perbaikan / Batal Transaksi, Pada Tanggal' . now(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Produk Berhasil Dibatalkan.']);
    }

    public function storePembelianLuarToko(Request $request)
    {
        $request->validate(
            [
                'kodepembelianproduk' => 'required',

                // Wajib isi salah satu
                'suplier' => 'required_without_all:pelanggan,nonsuplierdanpembeli|nullable|exists:suplier,id',
                'pelanggan' => 'required_without_all:suplier,nonsuplierdanpembeli|nullable|exists:pelanggan,id',
                'nonsuplierdanpembeli' => 'required_without_all:suplier,pelanggan|nullable|string',
            ],
            [
                'suplier.required_without_all' => 'Silakan pilih Suplier, Pelanggan, atau isi Non Suplier/Pelanggan.',
                'pelanggan.required_without_all' => 'Silakan pilih Suplier, Pelanggan, atau isi Non Suplier/Pelanggan.',
                'nonsuplierdanpembeli.required_without_all' => 'Silakan pilih Suplier, Pelanggan, atau isi Non Suplier/Pelanggan.',
            ]
        );

        $kodePembelian = (new PembelianController)->generateKodeTransaksiPembelian();
        $kodepembelianproduk = $request['kodepembelianproduk'];
        $tanggal = $request['tanggal']  = Carbon::today()->format('Y-m-d');

        if (!$kodepembelianproduk) {
            return response()->json(['success' => false, 'message' => 'Kode pembelian produk tidak ditemukan. Silakan ulangi proses.']);
        }

        // Step 1: Ambil semua kodeproduk dari pembelian_produk
        $kodeProdukList = PembelianProduk::where('status', 1)
            ->where('oleh', Auth::id())
            ->where('kodepembelianproduk', $request->kodepembelianproduk)
            ->where('jenispembelian', 2)
            ->pluck('kodeproduk');

        // Step 2: Ambil harga_beli dari produk berdasarkan kodeproduk
        $totalHargaBeli = PembelianProduk::whereIn('kodeproduk', $kodeProdukList)->where('status', 1)
            ->sum('harga_beli');

        if ($request->suplier_id != "" || $request->pelanggan_id == "") {
            $request['pelanggan'] = null;
        } elseif ($request->suplier_id == '' || $request->pelanggan_id != "") {
            $request['suplier'] = null;
        } elseif ($request->suplier_id == '' || $request->pelanggan_id == "" || $request->nonsuplierdanpembeli != "") {
            $request['suplier'] = null;
            $request['pelanggan'] = null;
        }

        $pembelianproduk = Pembelian::create([
            'kodepembelian'          =>  $kodePembelian,
            'kodepembelianproduk'    =>  $kodepembelianproduk,
            'pelanggan_id'           =>  $request->pelanggan,
            'suplier_id'             =>  $request->suplier,
            'pelanggan_id'           =>  $request->pelanggan,
            'nonsuplierdanpembeli'   =>  $request->nonsuplierdanpembeli,
            'tanggal'                =>  $tanggal,
            'total_harga'            =>  $totalHargaBeli,
            'catatan'                =>  $request->catatan,
            'oleh'                   =>  Auth::user()->id,
            'jenispembelian'         =>  2,
            'status'                 =>  1,
        ]);

        if ($pembelianproduk) {
            PembelianProduk::where('kodepembelianproduk', $kodepembelianproduk)
                ->where('status', 1)
                ->where('jenispembelian', 2)
                ->update([
                    'status'    => 2,
                ]);

            session()->forget('kodepembelianproduk');
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan'
        ]);
    }
}
