<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan model User diimpor
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna dari aplikasi mobile.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::query(); // Menggunakan User model

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('no_handphone', 'like', "%{$search}%");
        }

        $users = $query->latest()->paginate(15); // Mengurutkan dari yang terbaru & pagination

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Menampilkan detail satu pengguna.
     */
    public function show(User $user)
    {
        // Anda bisa menambahkan logika lain di sini jika diperlukan, misal riwayat pemesanan user
        return view('admin.users.show', compact('user'));
    }
}
