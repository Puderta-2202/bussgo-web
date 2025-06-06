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
        // 1. Validasi data yang masuk
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_handphone' => 'required|string|max:15|unique:users,no_handphone', // Validasi No HP
            'password' => 'required|string|min:8|confirmed', // 'confirmed' berarti harus ada field 'password_confirmation'
        ]);

        // 2. Buat user baru di database
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'no_handphone' => $validatedData['no_handphone'],
            'password' => Hash::make($validatedData['password']), // Password di-hash demi keamanan
        ]);

        // 3. Buat token API untuk user yang baru mendaftar
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Kirim respons kembali ke aplikasi mobile
        return response()->json([
            'message' => 'Registrasi berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201); // 201 artinya 'Created'
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
