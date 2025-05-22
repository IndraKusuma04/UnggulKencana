<?php

namespace App\Http\Controllers\Pembelian;

use Carbon\Carbon;
use App\Models\Produk;
use App\Models\Keranjang;
use App\Models\Pembelian;
use App\Models\Perbaikan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\PembelianProduk;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Perbaikan\PerbaikanController;

class PembelianTokoController extends Controller
{
    public function generateKodePembelianProduk()
    {
        $userId = Auth::id();

        // Cek apakah ada pembelian dengan status 1 untuk user ini
        $lastActive = DB::table('pembelian_produk')
            ->where('status', 1)
            ->where('oleh', $userId)
            ->orderBy('kodepembelianproduk', 'desc')
            ->first();

        if ($lastActive) {
            return $lastActive->kodepembelianproduk;
        }

        // Jika tidak ada, ambil kode terakhir untuk menentukan urutan
        $last = DB::table('pembelian_produk')
            ->orderBy('kodepembelianproduk', 'desc')
            ->first();

        $lastNumber = 1;
        if ($last) {
            $parts = explode('-', $last->kodepembelianproduk);
            $lastNumber = isset($parts[3]) ? ((int) $parts[3]) + 1 : 1;
        }

        $formattedNumber = str_pad($lastNumber, 4, '0', STR_PAD_LEFT);
        $timestamp = Carbon::now()->format('YmdHis');
        $random = rand(100, 999);

        $kode = 'PO-' . $timestamp . '-' . $random . '-' . $formattedNumber;

        return $kode;
    }

    public function getTransaksiByKodeTransaksi(Request $request)
    {

        if ($request->kodetransaksi) {
            $messages = [
                'required' => ':attribute wajib di isi !!!',
            ];

            $credentials = $request->validate([
                'kodetransaksi'       => 'required',
            ], $messages);

            $transaksi = Transaksi::with(['keranjang' => function ($query) {
                $query->where('status', '!=', 0);
            }, 'keranjang.produk', 'keranjang.produk.kondisi', 'pelanggan', 'user', 'user.pegawai', 'diskon'])
                ->where('kodetransaksi', $request->kodetransaksi)
                ->where('status', 2)
                ->get();

            // Cek apakah data transaksi ditemukan
            if ($transaksi->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan',
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Transaksi Berhasil Ditemukan', 'Data' => $transaksi]);
        } else {
            return response()->json(['success' => false, 'message' => 'Transaksi Belum Dicari, Silahkan Masukan Kode Transaksi']);
        }
    }

    public function getPembelianProduk()
    {
        $pembelianProduk = PembelianProduk::with(['jenisproduk', 'produk', 'kondisi'])
            ->where('status', 1)
            ->where('oleh', Auth::user()->id)
            ->where('jenispembelian', 1)
            ->get();

        return response()->json(['success' => true, 'message' => 'Data Pembelian Produk Berhasil Ditemukan', 'Data' => $pembelianProduk]);
    }

    public function storeProdukToPembelianProduk(Request $request)
    {
        $request->validate([
            'id'  => 'required|integer|exists:keranjang,id',
        ]);

        $keranjang = Keranjang::findOrFail($request->id);
        $produk = Produk::findOrFail($keranjang->produk_id);

        // Cek apakah kodeproduk sudah ada di pembelian_produk
        $existing = PembelianProduk::where('kodeproduk', $produk->kodeproduk)
            ->whereIn('status', [1, 2])
            ->first();

        if ($existing) {
            $message = $existing->status == 1
                ? 'Produk sudah ada di keranjang pembelian.'
                : 'Produk sudah masuk dalam transaksi pembelian.';

            return response()->json([
                'success' => false,
                'message' => $message,
            ]);
        }

        // Cek apakah sudah ada kodepembelianproduk di session
        $kodepembelianproduk = session('kodepembelianproduk');

        if (!$kodepembelianproduk) {
            $kodepembelianproduk = $this->generateKodePembelianProduk();
            session(['kodepembelianproduk' => $kodepembelianproduk]);
        }

        $InsertPembelianProduk = PembelianProduk::create([
            'kodepembelianproduk'   => $kodepembelianproduk,
            'kodeproduk'            => $produk->kodeproduk,
            'jenisproduk_id'        => $produk->jenisproduk_id,
            'kondisi_id'            => $produk->kondisi_id,
            'nama'                  => $produk->nama,
            'keterangan'            => $produk->keterangan,
            'harga_jual'            => $keranjang->harga_jual,
            'berat'                 => $keranjang->berat,
            'karat'                 => $keranjang->karat,
            'lingkar'               => $keranjang->lingkar,
            'panjang'               => $keranjang->panjang,
            'oleh'                  => Auth::user()->id,
            'jenispembelian'        => 1,
            'status'                => 1,
        ]);

        if ($InsertPembelianProduk) {
            // Selalu buat data perbaikan
            $perbaikanController = new PerbaikanController();
            $kodePerbaikan = $perbaikanController->kodePerbaikan();

            Perbaikan::create([
                'kodeperbaikan' => $kodePerbaikan,
                'produk_id'     => $produk->id,
                'kondisi_id'    => $produk->kondisi_id,
                'tanggalmasuk'  => now(),
                'status'        => 1,
                'keterangan'    => 'Masuk Tanggal ' . now()->format('Y-m-d H:i:s'),
                'oleh'          => Auth::user()->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke pembelian.',
            'kode'    => $kodepembelianproduk
        ]);
    }

    public function showPembelianProduk($id)
    {
        // Cari data pelanggan berdasarkan ID
        $produk = PembelianProduk::find($id);

        // Periksa apakah data ditemukan
        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Produk Berhasil Dibatalkan.', 'Data' => $produk]);
    }

    public function updatehargaPembelianProduk(Request $request, $id)
    {
        $produk = PembelianProduk::findOrFail($id);

        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'integer'  => ':attribute format wajib menggunakan angka',
        ];

        $credentials = $request->validate([
            'hargabeli' => 'required|integer',
            'kondisi'   => 'required|integer'
        ], $messages);

        // Update data pembelian produk
        $produk->update([
            'harga_beli' => $request->hargabeli,
            'kondisi_id' => $request->kondisi
        ]);

        // Ambil ID produk master dari kodeproduk
        $produkMaster = Produk::where('kodeproduk', $produk->kodeproduk)->first();

        // Jika ditemukan, update juga perbaikannya
        if ($produkMaster) {
            if (in_array($request->kondisi, [2, 3])) {
                // Jika kondisi rusak atau kusam, update kondisi di perbaikan
                Perbaikan::where('produk_id', $produkMaster->id)->update([
                    'kondisi_id' => $request->kondisi,
                    'status'     => 1
                ]);
            } elseif ($request->kondisi == 1) {
                // Jika kondisi bagus, update status perbaikan menjadi selesai
                Perbaikan::where('produk_id', $produkMaster->id)->update([
                    'kondisi_id' => 1,
                    'status'     => 0
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Disimpan']);
    }

    public function deletePembelianProduk($id)
    {
        // Cari data pelanggan berdasarkan ID
        $produk = PembelianProduk::find($id);
        $idproduk   = Produk::where('kodeproduk', $produk->kodeproduk)->first()->id;

        // Periksa apakah data ditemukan
        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $updateProduk = $produk->update([
            'status' => 0,
        ]);

        if ($updateProduk) {
            Perbaikan::where('produk_id', $idproduk)
                ->update([
                    'status' => 0,
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Produk Berhasil Dibatalkan.']);
    }

    public function storePembelianPelanggan(Request $request)
    {
        $request->validate([
            'kodepembelianproduk'   => 'required',
            'pelanggan'             => 'required|exists:pelanggan,id',
        ]);

        // Panggil method dari controller lain
        $kodePembelian = (new PembelianController)->generateKodeTransaksiPembelian();
        $tanggal = $request['tanggal']  = Carbon::today()->format('Y-m-d');
        $kodepembelianproduk = $request['kodepembelianproduk'];
        $pelanggan = $request['pelanggan'];
        $catatan = $request['catatan'];

        if (!$kodepembelianproduk) {
            return response()->json(['success' => false, 'message' => 'Kode pembelian produk tidak ditemukan. Silakan ulangi proses.']);
        }

        // Step 1: Ambil semua kodeproduk dari pembelian_produk
        $kodeProdukList = PembelianProduk::where('status', 1)
            ->where('oleh', Auth::id())
            ->where('kodepembelianproduk', $request->kodepembelianproduk)
            ->where('jenispembelian', 1)
            ->pluck('kodeproduk');

        // Step 2: Ambil harga_beli dari produk berdasarkan kodeproduk
        $totalHargaBeli = PembelianProduk::whereIn('kodeproduk', $kodeProdukList)->where('status', 1)
            ->sum('harga_beli');

        // Bisa juga ambil data lengkap jika diperlukan:
        $produkList = PembelianProduk::whereIn('kodeproduk', $kodeProdukList)->get();

        $pembelianproduk = Pembelian::create([
            'kodepembelian'          =>  $kodePembelian,
            'kodepembelianproduk'    =>  $kodepembelianproduk,
            'pelanggan_id'           =>  $pelanggan,
            'tanggal'                =>  $tanggal,
            'total_harga'            =>  $totalHargaBeli,
            'catatan'                =>  $catatan,
            'oleh'                   =>  Auth::user()->id,
            'jenispembelian'         =>  1,
            'status'                 =>  1,
        ]);

        if ($pembelianproduk) {
            PembelianProduk::where('kodepembelianproduk', $kodepembelianproduk)
                ->where('status', 1)
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
