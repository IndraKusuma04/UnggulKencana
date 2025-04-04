<?php

namespace App\Http\Controllers\Nampan;

use App\Http\Controllers\Controller;
use App\Models\Nampan;
use App\Models\NampanProduk;
use Illuminate\Http\Request;

class NampanController extends Controller
{
    public function getNampan()
    {
        $nampan = Nampan::where('status', 1)->with(['jenisProduk'])->get();

        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Ditemukan', 'Data' => $nampan]);
    }

    public function storeNampan(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
        ];

        $credentials = $request->validate([
            'jenis'         => 'required',
            'nampan'        => 'required',
        ], $messages);

        $storeNampan = Nampan::create([
            'jenisproduk_id'  =>  $request->jenis,
            'nampan'          =>  $request->nampan,
            'status'          =>  1,
        ]);

        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Disimpan']);
    }

    public function getNampanByID($id)
    {
        $nampan = Nampan::where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Data Nampan Berhasil Ditemukan', 'Data' => $nampan]);
    }

    public function updateNampan(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
        ];

        $credentials = $request->validate([
            'jenis'         => 'required',
            'nampan'        => 'required',
        ], $messages);

        // Cari data nampan berdasarkan ID
        $nampan = Nampan::where('id', $id)->first();

        // Periksa apakah data ditemukan
        if (!$nampan) {
            return response()->json(['success' => false, 'message' => 'Nampan tidak ditemukan.'], 404);
        }

        // Update data nampan
        $nampan->update([
            'nampan'            =>  $request->nampan,
            'jenisproduk_id'    =>  $request->jenis
        ]);

        return response()->json(['success' => true, 'message' => 'Nampan Berhasil Diperbarui.']);
    }

    public function deleteNampan($id)
    {
        // Cari data nampan berdasarkan ID
        $nampan = Nampan::find($id);

        // Periksa apakah data ditemukan
        if (!$nampan) {
            return response()->json(['success' => false, 'message' => 'Nampan tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $nampan->update([
            'status' => 0,
        ]);

        if ($nampan) {
            NampanProduk::where('nampan_id', $id)->update([
                'status'    => 0,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Nampan Berhasil Dihapus.']);
    }
}
