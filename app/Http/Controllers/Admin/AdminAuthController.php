<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSistem; // Pastikan model AdminSistem diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminAuthController extends Controller
{
    //--------------------------------------------------------------------------
    // Metode untuk Autentikasi Admin (Login, Register, Logout)
    //--------------------------------------------------------------------------

    /**
     * Menampilkan form login admin.
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin_sistem')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.admin_login');
    }

    /**
     * Memproses percobaan login admin.
     */
    public function login(Request $request)
    {
        // Nama field di form login Anda adalah 'username' (untuk input email/username) dan 'password'
        $validatedData = $request->validate([
            'username' => ['required', 'string', 'email', 'max:255'], // Validasi input 'username' dari form sebagai email
            'password' => ['required', 'string'],
        ]);

        // Kredensial untuk attempt() harus menggunakan nama kolom di database
        // Tabel admin_sistem Anda menggunakan 'email' untuk login
        $credentialsForAuth = [
            'email'    => $validatedData['username'], // Ambil nilai dari input 'username' form dan gunakan sebagai 'email'
            'password' => $validatedData['password'], // Ambil nilai dari input 'password' form
        ];

        if (Auth::guard('admin_sistem')->attempt($credentialsForAuth, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            // Kembalikan error ke field 'username' di form jika login gagal
            'username' => 'Kredensial yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Menampilkan form register admin (oleh admin lain atau initial setup).
     */
    public function showRegistrationForm()
    {
        // Biasanya, form register admin tidak selalu terbuka untuk umum
        // Mungkin hanya bisa diakses oleh admin lain yang sudah login,
        // atau saat setup awal. Untuk contoh ini, kita buat bisa diakses.
        if (Auth::guard('admin_sistem')->check() && !request()->routeIs('admin.admins.create')) {
            // Jika sudah login dan bukan mau buat admin baru via CRUD, redirect
            // Ini untuk mencegah akses ke /admin/register jika sudah login,
            // tapi tetap memperbolehkan akses ke /admin/admins/create
            // return redirect()->route('admin.dashboard');
        }
        // Jika ini adalah bagian dari CRUD Admin, view-nya mungkin beda (admin.admins.create)
        // Jika ini adalah halaman register mandiri untuk admin pertama:
        return view('auth.admin_register');
    }

    /**
     * Memproses registrasi admin baru (oleh admin lain atau initial setup).
     * Jika ini bagian dari CRUD Admin, method store() di AdminSistemController lebih tepat.
     * Method ini bisa digunakan untuk registrasi admin pertama jika belum ada.
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admin_sistem,email'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ]);

        AdminSistem::create($validatedData); // Model akan hash password

        // Jika registrasi dari halaman /admin/register, redirect ke login
        // Jika dari form CRUD admin, redirect ke admin.admins.index
        if ($request->routeIs('admin.register.submit')) {
            return redirect()->route('admin.login.form')->with('status', 'Admin baru berhasil diregistrasi! Silakan login.');
        }
        return redirect()->route('admin.admins.index')->with('success', 'Admin baru berhasil ditambahkan.');
    }

    /**
     * Logout admin.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin_sistem')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login.form')->with('status', 'Anda telah berhasil logout.');
    }


    //--------------------------------------------------------------------------
    // Metode untuk CRUD Admin Sistem (jika disatukan di sini)
    // Jika dipisah, ini seharusnya ada di AdminSistemController.php
    //--------------------------------------------------------------------------

    /**
     * Menampilkan daftar semua admin sistem.
     */
    public function indexAdmins(Request $request) // Ganti nama agar tidak konflik dengan index() lain jika ada
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $query = AdminSistem::orderBy('nama', 'asc');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }
        $admins = $query->paginate($perPage)->withQueryString();
        return view('admin.admins.index', compact('admins', 'search', 'perPage'));
    }

    /**
     * Menampilkan form untuk membuat admin sistem baru (dari dashboard).
     */
    public function createAdmin() // Ganti nama agar tidak konflik
    {
        return view('admin.admins.create');
    }

    /**
     * Menyimpan admin sistem baru ke database (dari dashboard).
     * Method 'store' sudah ada di atas sebagai 'register', kita bisa gunakan itu
     * atau buat method khusus jika logikanya berbeda. Untuk konsistensi CRUD,
     * lebih baik 'register' hanya untuk halaman register publik (jika ada)
     * dan 'storeAdmin' untuk CRUD oleh admin yang sudah login.
     *
     * Untuk contoh ini, kita akan asumsikan 'register' bisa dipakai,
     * tapi idealnya Anda punya method 'store' yang berbeda untuk CRUD admin.
     * Jika menggunakan 'register', pastikan redirectnya sesuai.
     */

    /**
     * Menampilkan form untuk mengedit data admin sistem.
     */
    public function editAdmin(AdminSistem $adminSistem) // Ganti nama dan gunakan route model binding
    {
        return view('admin.admins.edit', compact('adminSistem'));
    }

    /**
     * Memperbarui data admin sistem di database.
     */
    public function updateAdmin(Request $request, AdminSistem $adminSistem) // Ganti nama
    {
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admin_sistem', 'email')->ignore($adminSistem->id)],
            'password' => ['nullable', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ]);

        $dataToUpdate = [
            'nama' => $validatedData['nama'],
            'email' => $validatedData['email'],
        ];

        if (!empty($validatedData['password'])) {
            $dataToUpdate['password'] = $validatedData['password']; // Model akan hash
        }

        $adminSistem->update($dataToUpdate);
        return redirect()->route('admin.admins.index')->with('success', 'Data admin sistem berhasil diperbarui.');
    }

    /**
     * Menghapus data admin sistem dari database.
     */
    public function destroyAdmin(AdminSistem $adminSistem) // Ganti nama
    {
        if (Auth::guard('admin_sistem')->id() == $adminSistem->id) {
            return redirect()->route('admin.admins.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        if (AdminSistem::count() <= 1) {
            return redirect()->route('admin.admins.index')->with('error', 'Tidak dapat menghapus admin terakhir.');
        }
        try {
            $adminSistem->delete();
            return redirect()->route('admin.admins.index')->with('success', 'Data admin sistem berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.admins.index')->with('error', 'Gagal menghapus data admin sistem.');
        }
    }
}
