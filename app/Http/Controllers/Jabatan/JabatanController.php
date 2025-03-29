<?php

namespace App\Http\Controllers\Jabatan;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function getJabatan()
    {
        $jabatan = Jabatan::where('status', 1)->get();

        return response()->json(['success' => true, 'message' => 'Data Jabatan Berhasil Ditemukan', 'Data' => $jabatan]);
    }

    public function storeJabatan(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'unique'   => ':attribute sudah digunakan'
        ];

        $credentials = $request->validate([
            'jabatan'       =>  'required|unique:jabatan',
        ], $messages);

        $jabatan = Jabatan::create([
            'jabatan'       =>  $request->jabatan,
            'status'        =>  1,
        ]);

        return response()->json(['success' => true, 'message' => 'Data Jabatan Berhasil Disimpan']);
    }

    public function getJabatanByID($id)
    {
        $jabatan = Jabatan::where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Data Jabatan Berhasil Ditemukan', 'Data' => $jabatan]);
    }

    public function updateJabatan(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'unique'   => ':attribute sudah digunakan'
        ];

        $credentials = $request->validate([
            'jabatan'       =>  'required|unique:jabatan',
        ], $messages);

        // Cari data jabatan berdasarkan ID
        $jabatan = Jabatan::where('id', $id)->first();

        // Periksa apakah data ditemukan
        if (!$jabatan) {
            return response()->json(['success' => false, 'message' => 'Jabatan tidak ditemukan.'], 404);
        }

        // Update data jabatan
        $jabatan->update([
            'jabatan' => $request->jabatan,
        ]);

        return response()->json(['success' => true, 'message' => 'Jabatan Berhasil Diperbarui.']);
    }

    public function deleteJabatan($id)
    {
        // Cari data jabatan berdasarkan ID
        $jabatan = Jabatan::find($id);

        // Periksa apakah data ditemukan
        if (!$jabatan) {
            return response()->json(['success' => false, 'message' => 'Jabatan tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $jabatan->update([
            'status' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Jabatan Berhasil Dihapus.']);
    }
}
