<?php

namespace App\Http\Controllers\Users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function getUsers()
    {
        $users = User::with(['pegawai', 'role'])->where('status', 1)->get();

        return response()->json(['success' => true, 'message' => 'Data Users Berhasil Ditemukan', 'Data' => $users]);
    }

    public function getUsersByID($id)
    {
        $users = User::with(['pegawai', 'role'])->where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Data Users Berhasil Ditemukan', 'Data' => $users]);
    }

    public function updateUsers(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute wajib diisi !!!',
            'unique'   => ':attribute sudah digunakan',
            'min'      => ':attribute minimal :min karakter'
        ];

        // Ambil data user berdasarkan ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        // Cek apakah user sudah memiliki email dan password
        $hasEmail = !empty($user->email);
        $hasPassword = !empty($user->password);

        // Buat aturan validasi berdasarkan kondisi
        $rules = [];
        if (!$hasEmail || !$hasPassword) {
            // Jika user belum memiliki email & password (user baru)
            $rules = [
                'email'    => 'required|unique:users,email',
                'password' => 'required|min:6'
            ];
        } else {
            // Jika user sudah memiliki email & password
            if ($request->email !== $user->email) {
                // Jika email diubah, validasi email harus unik
                $rules['email'] = 'required|unique:users,email,' . $id;
            }

            if (!empty($request->password)) {
                // Jika password diisi, cek agar tidak sama dengan yang lama
                if (Hash::check($request->password, $user->password)) {
                    return response()->json(['success' => false, 'message' => 'Password baru tidak boleh sama dengan password lama.'], 400);
                }
                $rules['password'] = 'min:6';
            }
        }

        // Jalankan validasi
        $request->validate($rules, $messages);

        // Proses update data user
        $user->update([
            'email'    => $request->email ?? $user->email,
            'password' => !empty($request->password) ? Hash::make($request->password) : $user->password,
            'role_id'  => $request->role ?? $user->role_id
        ]);

        return response()->json(['success' => true, 'message' => "Data User Berhasil Diperbarui"]);
    }
}
