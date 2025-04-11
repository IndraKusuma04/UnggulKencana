<?php

namespace App\Http\Controllers\Pembelian;

use App\Models\Produk;
use App\Models\Keranjang;
use App\Models\Pembelian;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\PembelianProduk;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function getPembelian()
    {
        $pembelian = Pembelian::with(['suplier', 'pelanggan', 'pembelianproduk'])->get();

        return response()->json(['success' => true, 'message' => 'Data Pembelian Berhasil Ditemukan', 'Data' => $pembelian]);
    }

    public function getTransaksiByKodeTransaksi(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
        ];

        $credentials = $request->validate([
            'kodetransaksi'       => 'required',
        ], $messages);

        $transaksi = Transaksi::with(['keranjang' => function ($query) {
            $query->where('status', '!=', 0);
        }, 'keranjang.produk', 'pelanggan', 'user', 'user.pegawai', 'diskon'])->where('kodetransaksi', $request->kodetransaksi)->where('status', 2)->get();

        // Cek apakah data transaksi ditemukan
        if ($transaksi->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Transaksi Berhasil Ditemukan', 'Data' => $transaksi]);
    }

    public function generateKodePembelianProduk()
    {
        // Cek apakah ada kode keranjang terakhir dengan status 1
        $lastKeranjangWithStatusOne = DB::table('pembelian_produk')
            ->where('status', 1)
            ->orderBy('kodepembelianproduk', 'desc')
            ->first();

        // Jika ada kode keranjang dengan status 1, gunakan kode itu
        if ($lastKeranjangWithStatusOne) {
            return $lastKeranjangWithStatusOne->kodepembelianproduk;
        }

        // Jika tidak ada keranjang dengan status 1, ambil kode keranjang terakhir secara umum
        $lastKeranjang = DB::table('pembelian_produk')
            ->orderBy('kodepembelianproduk', 'desc')
            ->first();

        // Jika tidak ada keranjang sama sekali, mulai dari 1
        $lastNumber = $lastKeranjang ? (int) substr($lastKeranjang->kodepembelianproduk, -5) : 0;

        // Tambahkan 1 pada nomor terakhir
        $newNumber = $lastNumber + 1;

        // Format kode keranjang baru
        $newKodeKeranjang = '#PO-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return $newKodeKeranjang;
    }

    public function getPembelianProduk()
    {
        $pembelianProduk = PembelianProduk::where('status', 1)->get();

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
        $existing = PembelianProduk::where('kodeproduk', $produk->kodeproduk)->where('status', 1)->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sudah ada di keranjang pembelian.',
            ]);
        }

        $kodepembelianproduk = $this->generateKodePembelianProduk();

        PembelianProduk::create([
            'kodepembelianproduk'   => $kodepembelianproduk,
            'kodeproduk'            => $produk->kodeproduk,
            'jenisproduk_id'        => $produk->jenisproduk_id,
            'nama'                  => $produk->nama,
            'keterangan'            => $produk->keterangan,
            'harga_jual'            => $keranjang->harga_jual,
            'berat'                 => $keranjang->berat,
            'karat'                 => $keranjang->karat,
            'lingkar'               => $keranjang->lingkar,
            'panjang'               => $keranjang->panjang,
            'oleh'                  => Auth::user()->id,
            'status'                => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke pembelian.',
        ]);
    }
}
