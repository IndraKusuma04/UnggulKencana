<?php

namespace App\Http\Controllers\Diskon;

use App\Http\Controllers\Controller;
use App\Models\Diskon;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
    public function getDiskon()
    {
        $diskon = Diskon::where('status', 1)->get();

        return response()->json(['success' => true, 'message' => 'Data Diskon Berhasil Ditemukan', 'Data' => $diskon]);
    }

    public function storeDiskon(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'unique'   => ':attribute sudah digunakan',
            'numeric'  => ':attribute wajib menggunakan angka !!!',
        ];

        $credentials = $request->validate([
            'diskon'   =>  'required|unique:diskon',
            'nilai'    =>  'required|numeric'
        ], $messages);

        $diskon = Diskon::create([
            'diskon'    =>  $request->diskon,
            'nilai'     =>  $request->nilai,
            'status'    =>  1,
        ]);

        return response()->json(['success' => true, 'message' => 'Data Diskon Berhasil Disimpan']);
    }

    public function getDiskonByID($id)
    {
        $diskon = Diskon::where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Data Diskon Berhasil Ditemukan', 'Data' => $diskon]);
    }

    public function updateDiskon(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'unique'   => ':attribute sudah digunakan',
            'numeric'  => ':attribute wajib menggunakan angka !!!',
        ];

        $credentials = $request->validate([
            'diskon'   =>  'required|unique:diskon',
            'nilai'    =>  'required|numeric'
        ], $messages);

        // Cari data diskon berdasarkan ID
        $diskon = Diskon::where('id', $id)->first();

        // Periksa apakah data ditemukan
        if (!$diskon) {
            return response()->json(['success' => false, 'message' => 'Diskon / promo tidak ditemukan.'], 404);
        }

        // Update data diskon
        $diskon->update([
            'diskon' => $request->diskon,
            'nilai'  => $request->nilai
        ]);

        return response()->json(['success' => true, 'message' => 'Diskon Berhasil Diperbarui.']);
    }

    public function deleteDiskon($id)
    {
        // Cari data diskon berdasarkan ID
        $diskon = Diskon::find($id);

        // Periksa apakah data ditemukan
        if (!$diskon) {
            return response()->json(['success' => false, 'message' => 'Diskon tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $diskon->update([
            'status' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Diskon Berhasil Dihapus.']);
    }
}
