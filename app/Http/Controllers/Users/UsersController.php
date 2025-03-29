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
        $users = User::where('id', $id)->first();

        if (!$users) {
            return response()->json(['success' => false, 'message' => 'Users tidak ditemukan.'], 404);
        }

        // Cek apakah email diubah
        $rules = [
            'password' => 'nullable|min:6', // Password boleh kosong tetapi minimal 6 karakter jika diisi
        ];

        if ($request->email !== $users->email) {
            $rules['email'] = 'required|unique:users,email';
        }

        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'unique' => ':attribute sudah digunakan',
            'min' => ':attribute minimal :min karakter'
        ];

        $credentials = $request->validate($rules, $messages);

        // Jika password baru sama dengan password lama, tolak update
        if ($request->password && Hash::check($request->password, $users->password)) {
            return response()->json(['success' => false, 'message' => 'Password baru tidak boleh sama dengan password lama.'], 400);
        }

        // Update user
        $users->update([
            'email' => $request->email ?? $users->email, // Gunakan email lama jika tidak diubah
            'password' => $request->password ? Hash::make($request->password) : $users->password,
            'role_id' => $request->role ?? $users->role_id
        ]);

        return response()->json(['success' => true, 'message' => 'Users Berhasil Diperbarui.']);
    }
}
