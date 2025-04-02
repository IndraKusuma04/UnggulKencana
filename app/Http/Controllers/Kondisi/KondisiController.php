<?php

namespace App\Http\Controllers\Kondisi;

use App\Http\Controllers\Controller;
use App\Models\Kondisi;
use Illuminate\Http\Request;

class KondisiController extends Controller
{
    public function getKondisi()
    {
        $kondisi = Kondisi::where('status', 1)->get();

        return response()->json(['success' => true, 'message' => 'Data Kondisi Berhasil Ditemukan', 'Data' => $kondisi]);
    }

    public function storeKondisi(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'unique'   => ':attribute sudah digunakan'
        ];

        $credentials = $request->validate([
            'kondisi'       =>  'required|unique:kondisi',
        ], $messages);

        $Kondisi = Kondisi::create([
            'kondisi'       =>  $request->kondisi,
            'status'        =>  1,
        ]);

        return response()->json(['success' => true, 'message' => 'Data Kondisi Berhasil Disimpan']);
    }

    public function getKondisiByID($id)
    {
        $kondisi = Kondisi::where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Data Kondisi Berhasil Ditemukan', 'Data' => $kondisi]);
    }

    public function updateKondisi(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'unique'   => ':attribute sudah digunakan'
        ];

        $credentials = $request->validate([
            'kondisi'       =>  'required|unique:kondisi',
        ], $messages);

        // Cari data kondisi berdasarkan ID
        $kondisi = Kondisi::where('id', $id)->first();

        // Periksa apakah data ditemukan
        if (!$kondisi) {
            return response()->json(['success' => false, 'message' => 'Kondisi tidak ditemukan.'], 404);
        }

        // Update data kondisi
        $kondisi->update([
            'kondisi' => $request->kondisi,
        ]);

        return response()->json(['success' => true, 'message' => 'Kondisi Berhasil Diperbarui.']);
    }

    public function deletekondisi($id)
    {
        // Cari data kondisi berdasarkan ID
        $kondisi = Kondisi::find($id);

        // Periksa apakah data ditemukan
        if (!$kondisi) {
            return response()->json(['success' => false, 'message' => 'Kondisi tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $kondisi->update([
            'status' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Kondisi Berhasil Dihapus.']);
    }
}
