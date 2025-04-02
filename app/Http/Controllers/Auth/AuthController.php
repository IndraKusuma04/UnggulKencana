<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function login(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib di isi !!!',
        ];

        $credentials = $request->validate([
            'email'    => 'required',
            'password' => 'required',
        ], $messages);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->status != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'User Account Belum Aktif !'
                ]);
            } else {
                $user = Auth::user();
                $pegawai = Pegawai::where('id', $user->pegawai_id)->first();
                $jabatan = Jabatan::where('id', $pegawai->jabatan_id)->first()->jabatan;
                $role = Role::where('id', $user->role_id)->first()->role;

                Session::put('nama', $pegawai->nama);
                Session::put('image', $pegawai->image);
                Session::put('jabatan', $jabatan);
                Session::put('role', $role);

                // Redirect berdasarkan role user
                if ($role === 'ADMIN') {
                    $redirectUrl = url('/admin/dashboard');
                } elseif ($role === 'OWNER') {
                    $redirectUrl = url('/owner/dashboard');
                } elseif ($role === 'PEGAWAI') {
                    $redirectUrl = url('/pegawai/dashboard');
                } else {
                    $redirectUrl = url('/'); // Jika role tidak dikenali
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => $redirectUrl
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah!'
        ]);
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Anda sudah logout.');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success-message', 'Logout Berhasil');
    }
}
