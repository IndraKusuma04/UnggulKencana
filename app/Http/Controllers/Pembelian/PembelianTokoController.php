<?php

namespace App\Http\Controllers\Pembelian;

use Carbon\Carbon;
use App\Models\Produk;
use App\Models\Keranjang;
use App\Models\Pembelian;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\PembelianProduk;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PembelianTokoController extends Controller
{
    public function generateKodePembelianProduk()
    {
        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Cek apakah ada kode keranjang terakhir dengan status 1 untuk user tersebut
        $lastKeranjangWithStatusOne = DB::table('pembelian_produk')
            ->where('status', 1)
            ->where('oleh', $userId) // Pastikan hanya mengambil milik user ini
            ->orderBy('kodepembelianproduk', 'desc')
            ->first();

        // Jika ada kode keranjang dengan status 1, gunakan kode itu
        if ($lastKeranjangWithStatusOne) {
            return $lastKeranjangWithStatusOne->kodepembelianproduk;
        }

        // Jika tidak ada keranjang dengan status 1, ambil kode keranjang terakhir untuk user ini
        $lastKeranjang = DB::table('pembelian_produk')
            ->where('oleh', $userId) // Pastikan hanya mengambil milik user ini
            ->orderBy('kodepembelianproduk', 'desc')
            ->first();

        // Jika tidak ada keranjang sama sekali, mulai dari 1
        $lastNumber = $lastKeranjang ? (int) substr($lastKeranjang->kodepembelianproduk, -5) : 0;

        // Tambahkan 1 pada nomor terakhir
        $newNumber = $lastNumber + 1;

        // Format kode keranjang baru dengan menambahkan ID user sebagai prefix
        $newKodeKeranjang = '#PO-' . $userId . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return $newKodeKeranjang;
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

        PembelianProduk::create([
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
        $produk = PembelianProduk::where('id', $id)->first();

        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'integer'  => ':attribute format wajib menggunakan angka',
        ];

        $credentials = $request->validate([
            'hargabeli'    =>  'integer',
        ], $messages);

        $updateProduk = PembelianProduk::where('id', $id)
            ->update([
                'harga_beli'    =>  $request->hargabeli,
                'kondisi_id'    =>  $request->kondisi
            ]);

        return response()->json(['success' => true, 'message' => 'Data Produk Berhasil Disimpan']);
    }

    public function deletePembelianProduk($id)
    {
        // Cari data pelanggan berdasarkan ID
        $produk = PembelianProduk::find($id);

        // Periksa apakah data ditemukan
        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $produk->update([
            'status' => 0,
        ]);

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
