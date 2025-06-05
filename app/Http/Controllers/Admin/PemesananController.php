<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan; // Pastikan model Pemesanan Anda sudah ada
use Illuminate\Http\Request;
use Carbon\Carbon; // Jika Anda menggunakan Carbon untuk manipulasi tanggal di controller

class PemesananController extends Controller
{
    /**
     * Menampilkan daftar pemesanan.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $showEntries = $request->input('show', 10);

        // Menggunakan relasi 'jadwalKeberangkatan' sesuai model Pemesanan.php
        $query = Pemesanan::with(['user', 'jadwalKeberangkatan.bus'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pemesan', 'like', "%{$search}%")
                    ->orWhere('kode_booking', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    })
                    // Menggunakan relasi 'jadwalKeberangkatan'
                    ->orWhereHas('jadwalKeberangkatan', function ($kebQuery) use ($search) {
                        $kebQuery->whereHas('bus', function ($busQuery) use ($search) {
                            $busQuery->where('nama_bus', 'like', "%{$search}%");
                        })
                            ->orWhere('asal', 'like', "%{$search}%") // Tambahan: cari berdasarkan asal jadwal
                            ->orWhere('tujuan', 'like', "%{$search}%"); // Tambahan: cari berdasarkan tujuan jadwal
                    });
            });
        }

        $pemesanan = $query->paginate($showEntries);

        return view('admin.pemesanan.index', compact('pemesanan', 'search', 'showEntries'));
    }

    /**
     * Menampilkan detail satu pemesanan.
     */
    public function show(Pemesanan $pemesanan)
    {
        // Menggunakan relasi 'jadwalKeberangkatan'
        // Relasi 'bus' di sini merujuk ke bus_id langsung di tabel pemesanan, jika masih relevan.
        // Jika bus hanya diakses melalui jadwalKeberangkatan, maka 'bus' di sini bisa dihilangkan dari load.
        $pemesanan->load(['user', 'bus', 'jadwalKeberangkatan', 'jadwalKeberangkatan.bus']);
        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    /**
     * Menghapus data pemesanan.
     */
    public function destroy(Pemesanan $pemesanan)
    {
        $pemesanan->delete();
        return redirect()->route('admin.pemesanan.index')->with('success', 'Data pemesanan berhasil dihapus.');
    }

    /**
     * Method untuk mencetak tiket/bukti pemesanan.
     */
    public function cetak(Pemesanan $pemesanan)
    {
        // Menggunakan relasi 'jadwalKeberangkatan'
        $pemesanan->load(['user', 'bus', 'jadwalKeberangkatan', 'jadwalKeberangkatan.bus']);

        // Anda bisa menggunakan library PDF di sini
        // Contoh:
        // $pdf = PDF::loadView('admin.pemesanan.cetak_template', compact('pemesanan'));
        // return $pdf->download('bukti_pemesanan_'.$pemesanan->kode_booking.'.pdf');

        return view('admin.pemesanan.cetak_preview', compact('pemesanan'));
    }
}
