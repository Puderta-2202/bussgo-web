<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan model User Anda diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menangani permintaan registrasi dari aplikasi mobile.
     */
    public function register(Request $request)
    {
        // 1. Ubah validasi dari 'name' menjadi 'nama_lengkap'
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_handphone' => 'required|string|max:15|unique:users,no_handphone',
            'alamat' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Ubah data yang dibuat dari 'name' menjadi 'nama_lengkap'
        $user = User::create([
            'nama_lengkap' => $validatedData['nama_lengkap'],
            'email' => $validatedData['email'],
            'no_handphone' => $validatedData['no_handphone'],
            'alamat' => $validatedData['alamat'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Bagian token tetap sama
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    /**
     * Menangani permintaan login dari aplikasi mobile.
     */
    public function login(Request $request)
    {
        // 1. Validasi data login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba otentikasi pengguna
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Jika gagal, kirim pesan error
            return response()->json(['message' => 'Email atau password salah.'], 401); // 401 artinya 'Unauthorized'
        }

        // 3. Jika berhasil, dapatkan data user dan buat token baru
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Kirim respons berhasil beserta token ke aplikasi mobile
        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Menangani permintaan logout dari aplikasi mobile.
     */
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan untuk otentikasi
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }
}
