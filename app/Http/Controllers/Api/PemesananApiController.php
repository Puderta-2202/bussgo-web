<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\JadwalKeberangkatan;
use App\Http\Resources\PemesananResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PemesananApiController extends Controller
{
    /**
     * Membuat pemesanan tiket baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'keberangkatan_id' => 'required|exists:keberangkatan,id',
            'jumlah_tiket' => 'required|integer|min:1',
        ]);

        $jadwal = JadwalKeberangkatan::find($validatedData['keberangkatan_id']);
        $user = Auth::user(); // Mendapatkan user yang sedang login via token
        $totalHarga = $jadwal->harga * $validatedData['jumlah_tiket'];

        // Gunakan DB::transaction untuk memastikan kedua operasi (membuat pesanan dan mengurangi kursi) berhasil atau keduanya gagal.
        try {
            DB::beginTransaction();

            // 1. Validasi ketersediaan kursi
            if ($jadwal->jumlah_kursi_tersedia < $validatedData['jumlah_tiket']) {
                // Batalkan transaksi jika kursi tidak cukup
                DB::rollBack();
                return response()->json(['message' => 'Maaf, jumlah kursi yang tersedia tidak mencukupi.'], 422); // 422 Unprocessable Entity
            }

            // 2. Buat catatan pemesanan baru
            $pemesanan = Pemesanan::create([
                'user_id' => $user->id,
                'bus_id' => $jadwal->bus_id,
                'keberangkatan_id' => $jadwal->id,
                'kode_booking' => 'BOOK-' . strtoupper(Str::random(8)),
                'nama_pemesan' => $user->name,
                'email_pemesan' => $user->email,
                'telepon_pemesan' => $user->no_handphone, // Mengambil dari data user
                'jumlah_tiket' => $validatedData['jumlah_tiket'],
                'total_harga' => $totalHarga,
                'status_pembayaran' => 'pending', // Status awal, sebelum pembayaran
            ]);

            // 3. Kurangi jumlah kursi yang tersedia di jadwal
            $jadwal->decrement('jumlah_kursi_tersedia', $validatedData['jumlah_tiket']);

            // Jika semua berhasil, commit transaksi
            DB::commit();

            // Muat relasi untuk ditampilkan di respons
            $pemesanan->load('jadwalKeberangkatan.bus');

            return response()->json([
                'message' => 'Pemesanan berhasil dibuat. Silakan lanjutkan ke pembayaran.',
                'data' => new PemesananResource($pemesanan)
            ], 201); // 201 Created

        } catch (\Exception $e) {
            // Jika terjadi error, batalkan semua operasi database
            DB::rollBack();
            // Kirim respons error
            return response()->json(['message' => 'Terjadi kesalahan saat memproses pemesanan.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan riwayat pemesanan milik pengguna yang sedang login.
     */
    public function riwayatPemesanan()
    {
        $riwayat = Pemesanan::where('user_id', Auth::id())
            ->with('jadwalKeberangkatan.bus')
            ->latest() // Urutkan dari yang terbaru
            ->paginate(10);

        return PemesananResource::collection($riwayat);
    }

    /**
     * Menampilkan detail satu pemesanan.
     */
    public function showPemesananDetail(Pemesanan $pemesanan)
    {
        // Pastikan pengguna hanya bisa melihat pemesanannya sendiri
        if ($pemesanan->user_id !== Auth::id()) {
            return response()->json(['message' => 'Akses ditolak.'], 403); // 403 Forbidden
        }

        $pemesanan->load('jadwalKeberangkatan.bus');
        return new PemesananResource($pemesanan);
    }
}
