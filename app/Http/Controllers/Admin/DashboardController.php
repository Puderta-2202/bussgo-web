<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan info admin yang login

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index()
    {
        // Ambil nama admin yang sedang login
        $adminName = Auth::guard('admin_sistem')->user()->nama ?? 'Admin'; // Ambil nama, atau 'Admin' jika tidak ada

        return view('admin.dashboard', ['adminName' => $adminName]);
    }
}