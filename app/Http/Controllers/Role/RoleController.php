<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function getRole()
    {
        $role = Role::where('status', 1)->get();

        return response()->json(['success' => true, 'message' => 'Data Role Ditemukan', 'Data' => $role]);
    }

    public function storeRole(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'unique'   => ':attribute sudah digunakan'
        ];

        $credentials = $request->validate([
            'role'       =>  'required|unique:role',
        ], $messages);

        $role = Role::create([
            'role'      =>  $request->role,
            'status'    =>  1,
        ]);

        return response()->json(['success' => true, 'message' => 'Data Role Berhasil Disimpan']);
    }

    public function getRoleByID($id)
    {
        $role = Role::where('id', $id)->get();

        return response()->json(['success' => true, 'message' => 'Data Role Berhasil Ditemukan', 'Data' => $role]);
    }

    public function updateRole(Request $request, $id)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
            'unique'   => ':attribute sudah digunakan'
        ];

        $credentials = $request->validate([
            'role'       =>  'required|unique:role',
        ], $messages);

        // Cari data role berdasarkan ID
        $role = Role::where('id', $id)->first();

        // Periksa apakah data ditemukan
        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role tidak ditemukan.'], 404);
        }

        // Update data role
        $role->update([
            'role' => $request->role,
        ]);

        return response()->json(['success' => true, 'message' => 'Role Berhasil Diperbarui.']);
    }

    public function deleteRole($id)
    {
        // Cari data role berdasarkan ID
        $role = Role::find($id);

        // Periksa apakah data ditemukan
        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role tidak ditemukan.'], 404);
        }

        // Update status menjadi 0 (soft delete manual)
        $role->update([
            'status' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Role Berhasil Dihapus.']);
    }
}
