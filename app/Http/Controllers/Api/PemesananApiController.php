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
            'nomor_kursi_dipesan' => 'required|array',
            'nomor_kursi_dipesan.*' => 'required|string',
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
                'nama_pemesan' => $user->nama_lengkap, // Mengambil dari data user
                'email_pemesan' => $user->email,
                'telepon_pemesan' => $user->no_handphone, // Mengambil dari data user
                'jumlah_tiket' => $validatedData['jumlah_tiket'],
                'nomor_kursi_dipesan' => $validatedData['nomor_kursi_dipesan'],
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

    /**
     * Memproses pembayaran yang menggunakan saldo internal (BusPay).
     */
    public function bayarDenganSaldo(Request $request)
    {
        // 1. Validasi input: pastikan pemesanan_id dikirim
        $validatedData = $request->validate([
            'pemesanan_id' => 'required|exists:pemesanan,id',
        ]);

        $user = Auth::user();
        if (!($user instanceof \Illuminate\Database\Eloquent\Model)) {
            $user = \App\Models\User::find(Auth::id());
        }
        $pemesanan = Pemesanan::find($validatedData['pemesanan_id']);

        // 2. Keamanan: Pastikan pemesanan ini milik user yang sedang login
        if ($pemesanan->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        // 3. Keamanan: Pastikan pemesanan ini belum pernah dibayar
        if ($pemesanan->status_pembayaran !== 'pending') {
            return response()->json(['message' => 'Pemesanan ini sudah diproses.'], 422);
        }

        // 4. Logika Bisnis: Pastikan saldo pengguna mencukupi
        if ($user->saldo < $pemesanan->total_harga) {
            return response()->json(['message' => 'Saldo BusPay Anda tidak mencukupi.'], 422);
        }

        // 5. Proses Inti: Gunakan transaction untuk keamanan data
        try {
            DB::beginTransaction();

            // a. Potong saldo user
            $user->saldo = $user->saldo - $pemesanan->total_harga;
            $user->save();

            // b. Update status pemesanan menjadi 'berhasil'
            $pemesanan->status_pembayaran = 'berhasil';
            $pemesanan->metode_pembayaran = 'saldo'; // Tandai metode pembayarannya
            $pemesanan->save();

            DB::commit(); // Simpan semua perubahan ke database jika tidak ada error

            return response()->json(['message' => 'Pembayaran dengan saldo berhasil!']);
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            return response()->json(['message' => 'Terjadi kesalahan saat memproses pembayaran.'], 500);
        }
    }
}
