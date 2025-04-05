<?php

namespace App\Http\Controllers\Suplier;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Suplier;

class SuplierController extends Controller
{
    private function generateCodeSuplier()
    {
        // Ambil kode customer terakhir dari database
        $lastCustomer = DB::table('suplier')
            ->orderBy('kodesuplier', 'desc')
            ->first();

        // Jika tidak ada customer, mulai dari 1
        $lastNumber = $lastCustomer ? (int) substr($lastCustomer->kodesuplier, -5) : 0;

        // Tambahkan 1 pada nomor terakhir
        $newNumber = $lastNumber + 1;

        // Format kode customer baru
        $newKodeCustomer = '#S-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return $newKodeCustomer;
    }

    public function getSuplier()
    {
        $suplier = Suplier::where('status', 1)->get();

        return response()->json(['success' => true, 'message' => 'Data Suplier Berhasil Ditemukan', 'Data' => $suplier]);
    }

    public function storeSuplier(Request $request)
    {
        $messages = [
            'required'  => ':attribute wajib di isi !!!',
            'integer'   => ':attribute format wajib menggunakan angka',
            'numeric'   => ':attribute format wajib menggunakan angka',
        ];

        $credentials = $request->validate([
            'suplier'         => 'required',
            'alamat'          => 'required',
            'kontak'          => 'required|numeric',
        ], $messages);

        $generateCode = $this->generateCodeSuplier();

        $suplier = Suplier::create([
            'kodesuplier'   =>  $generateCode,
            'nama'          =>  $request->suplier,
            'kontak'        =>  $request->kontak,
            'alamat'        =>  $request->alamat,
            'status'        =>  1,
        ]);

        return response()->json(['success' => true, 'message' => 'Data Suplier Berhasil Disimpan']);
    }

    public function getSuplierByID($id)
    {
        $suplier = Suplier::where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Data Suplier Berhasil Ditemukan', 'Data' => $suplier]);
    }

    public function updateSuplier(Request $request, $id)
    {
        $messages = [
            'required'  => ':attribute wajib di isi !!!',
            'integer'   => ':attribute format wajib menggunakan angka',
            'numeric'   => ':attribute format wajib menggunakan angka',
        ];

        $credentials = $request->validate([
            'suplier'         => 'required',
            'alamat'          => 'required',
            'kontak'          => 'required|numeric',
        ], $messages);

        Suplier::where('id', $id)
            ->update([
                'nama'          =>  $request->suplier,
                'kontak'        =>  $request->kontak,
                'alamat'        =>  $request->alamat,
            ]);

        return response()->json(['success' => true, 'message' => 'Data Suplier Berhasil Diperbarui']);
    }

    public function deleteSuplier($id)
    {
        // Cari data suplier berdasarkan ID
        $suplier = Suplier::find($id);

        // Periksa apakah data ditemukan
        if (!$suplier) {
            return response()->json(['success' => false, 'message' => 'Suplier tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $suplier->update([
            'status' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Suplier Berhasil Dihapus.']);
    }
}
