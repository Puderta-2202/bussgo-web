<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalKeberangkatan; // Pastikan nama model ini benar
use App\Http\Resources\JadwalResource;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal keberangkatan yang aktif dan akan datang.
     */
    public function index()
    {
        // Ambil data jadwal yang:
        // 1. Statusnya 'aktif'
        // 2. Tanggal keberangkatannya adalah hari ini atau di masa depan
        // 3. Muat relasi 'bus' untuk menghindari N+1 problem (Eager Loading)
        // 4. Urutkan berdasarkan tanggal terdekat
        $jadwal = JadwalKeberangkatan::with('bus')
            ->where('status_jadwal', 'aktif')
            ->where('tanggal_berangkat', '>=', Carbon::today()->toDateString())
            ->orderBy('tanggal_berangkat', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->paginate(15); // Gunakan paginate agar data tidak terlalu besar

        // Kembalikan data dalam bentuk koleksi resource
        return JadwalResource::collection($jadwal);
    }

    /**
     * Menampilkan detail satu jadwal keberangkatan.
     */
    public function show($id)
    {
        $jadwal = JadwalKeberangkatan::with('bus')->find($id);

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal keberangkatan tidak ditemukan.'], 404);
        }

        // Kembalikan data dalam bentuk satu resource
        return new JadwalResource($jadwal);
    }
}
