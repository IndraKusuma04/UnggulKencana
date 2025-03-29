<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class PegawaiController extends Controller
{
    public function getPegawai()
    {
        $pegawai = Pegawai::with(['jabatan'])->where('status', 1)->get();
        return response()->json(['success' => true, 'message' => 'Data Pegawai Berhasil Ditemukan', 'Data' => $pegawai]);
    }

    public function storePegawai(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'mimes'    => ':attribute format wajib menggunakan PNG/JPG',
            'unique'   => ':attribute sudah digunakan'
        ];

        $credentials = $request->validate([
            'nip'           => 'required|unique:pegawai',
            'jabatan'       => 'required',
            'imagePegawai'  => 'mimes:png,jpg,jpeg',
        ], $messages);

        $newImagePegawai = '';

        if ($request->file('imagePegawai')) {
            $extension = $request->file('imagePegawai')->getClientOriginalExtension();
            $newImagePegawai = $request->nip . '.' . $extension;
            $request->file('imagePegawai')->storeAs('avatar', $newImagePegawai);
            $request['imagePegawai'] = $newImagePegawai;
        }

        $store = Pegawai::create([
            'nip'           => $request->nip,
            'nama'          => $request->nama,
            'alamat'        => $request->alamat,
            'kontak'        => $request->kontak,
            'jabatan_id'    => $request->jabatan,
            'status'        => 1,
            'image_pegawai' => $newImagePegawai,
        ]);

        $pegawai_id = Pegawai::where('nip', '=', $request->nip)->first()->id;

        if ($store) {
            User::create([
                'pegawai_id' => $pegawai_id,
                'status'     => 1
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Data Pegawai Berhasil Disimpan']);
    }

    public function getPegawaiByID($id)
    {
        $pegawai = Pegawai::where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Data Pegawai Berhasil Ditemukan', 'Data' => $pegawai]);
    }

    public function updatePegawai(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'mimes'    => ':attribute format wajib menggunakan PNG/JPG',
            'unique'   => ':attribute sudah digunakan'
        ];

        $credentials = $request->validate([
            'jabatan'       => 'required',
            'imagePegawai'  => 'mimes:png,jpg,jpeg',
        ], $messages);

        $pegawai = Pegawai::where('nip', $id)->first();

        if ($request->file('imagePegawai')) {
            $pathavatar     = 'storage/avatar/' . $pegawai->image_pegawai;

            if (File::exists($pathavatar)) {
                File::delete($pathavatar);
            }

            $extension = $request->file('imagePegawai')->getClientOriginalExtension();
            $newImagePegawai = $request->nip . '.' . $extension;
            $request->file('imagePegawai')->storeAs('avatar', $newImagePegawai);
            $request['imagePegawai'] = $newImagePegawai;

            $updatepegawai = Pegawai::where('nip', $id)
                ->update([
                    'nama'          => $request->nama,
                    'alamat'        => $request->alamat,
                    'kontak'        => $request->kontak,
                    'jabatan_id'    => $request->jabatan,
                    'image_pegawai' => $newImagePegawai,
                ]);
        } else {
            $updatepegawai = Pegawai::where('nip', $id)
                ->update([
                    'nama'          => $request->nama,
                    'alamat'        => $request->alamat,
                    'kontak'        => $request->kontak,
                    'jabatan_id'    => $request->jabatan,
                ]);
        }
        return response()->json(['success' => true, 'message' => "Data Pegawai Berhasil Disimpan", 'Data' => $pegawai]);
    }

    public function deletePegawai($id)
    {
        // Cari data jabatan berdasarkan ID
        $pegawai = Pegawai::find($id);

        // Periksa apakah data ditemukan
        if (!$pegawai) {
            return response()->json(['success' => false, 'message' => 'Pegawai tidak ditemukan.'], 404);
        }


        // Update status menjadi 0 (soft delete manual)
        $update = $pegawai->update([
            'status' => 0,
        ]);
        if ($update) {
            User::where('pegawai_id', $id)
                ->update([
                    'status'     => 0
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Pegawai Berhasil Dihapus.']);
    }
}
