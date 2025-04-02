<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // Jika user belum login, arahkan ke halaman login
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika user sudah login dan mencoba akses halaman login, redirect ke dashboard
        if ($request->route()->named('login')) {
            return $this->redirectToDashboard(Auth::user());
        }

        // Jika parameter role diberikan, pastikan user memiliki role_id yang sesuai
        if ($role && !$this->hasRole(Auth::user()->role_id, $role)) {
            return response()->view('components.error', [
                'code' => 403,
                'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini.'
            ], 403);
        }

        return $next($request);
    }

    /**
     * Periksa apakah user memiliki role tertentu berdasarkan role_id.
     */
    private function hasRole($roleId, $roleName)
    {
        return match ($roleId) {
            1 => $roleName === 'admin',
            2 => $roleName === 'owner',
            3 => $roleName === 'pegawai',
            default => false,
        };
    }

    /**
     * Redirect user ke dashboard sesuai role.
     */
    private function redirectToDashboard($user)
    {
        return match ($user->role_id) {
            1 => redirect('/admin/dashboard'),
            2 => redirect('/owner/dashboard'),
            3 => redirect('/pegawai/dashboard'),
            default => redirect('/login'),
        };
    }
}
