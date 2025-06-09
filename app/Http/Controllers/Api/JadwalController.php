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
    public function index(Request $request) // <-- Tambahkan Request $request
    {
        $query = JadwalKeberangkatan::with('bus')
            ->where('status_jadwal', 'aktif')
            // Filter tanggal agar hanya menampilkan yang akan datang
            ->where('tanggal_berangkat', '>=', Carbon::today()->toDateString());

        // --- Tambahkan logika filter di sini ---
        if ($request->has('asal')) {
            $query->where('asal', 'like', '%' . $request->input('asal') . '%');
        }

        if ($request->has('tujuan')) {
            $query->where('tujuan', 'like', '%' . $request->input('tujuan') . '%');
        }

        // Filter tanggal spesifik jika diberikan
        if ($request->has('tanggal')) {
            $query->whereDate('tanggal_berangkat', $request->input('tanggal'));
        }

        $jadwal = $query->orderBy('tanggal_berangkat', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->paginate(15);

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
