<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    private function generateKodeTransaksi()
    {
        // Ambil kode customer terakhir dari database
        $lastCustomer = DB::table('transaksi')
            ->orderBy('kodetransaksi', 'desc')
            ->first();

        // Jika tidak ada customer, mulai dari 1
        $lastNumber = $lastCustomer ? (int) substr($lastCustomer->kodetransaksi, -5) : 0;

        // Tambahkan 1 pada nomor terakhir
        $newNumber = $lastNumber + 1;

        // Format kode customer baru
        $newKodeCustomer = Carbon::now()->format('YmdHis') . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return $newKodeCustomer;
    }

    public function getKodeTransaksi()
    {
        $kodetransaksi = $this->generateKodeTransaksi();
        return response()->json(['success' => true, 'kodetransaksi' => $kodetransaksi]);
    }
}
