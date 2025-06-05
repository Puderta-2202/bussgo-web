<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKeberangkatan;
use App\Models\Bus; // Pastikan model Bus ada dan di-import
use Illuminate\Http\Request;
use Carbon\Carbon;

class KeberangkatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $showEntries = $request->input('show', 10);

        $query = JadwalKeberangkatan::with('bus')->orderBy('tanggal_berangkat', 'desc')->orderBy('jam_berangkat', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('asal', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhereHas('bus', function ($busQuery) use ($search) {
                        $busQuery->where('nama_bus', 'like', "%{$search}%");
                    });
            });
        }

        $keberangkatan = $query->paginate($showEntries);

        // Menambahkan atribut 'status_perjalanan_view' untuk tampilan
        $keberangkatan->getCollection()->transform(function ($item) {
            $tanggalWaktuBerangkat = Carbon::parse($item->tanggal_berangkat->toDateString() . ' ' . $item->jam_berangkat);

            if ($item->status_jadwal === 'aktif' && $tanggalWaktuBerangkat->isFuture()) {
                $item->status_perjalanan_view = 'Belum Berangkat';
                $item->status_badge_class = 'info';
            } elseif ($item->status_jadwal === 'aktif' && ($tanggalWaktuBerangkat->isPast() || $tanggalWaktuBerangkat->isToday())) {
                // Anda bisa tambahkan logika jika jam sekarang sudah melewati jam berangkat
                $item->status_perjalanan_view = 'Sedang Berjalan / Telah Berangkat'; // Sesuaikan
                $item->status_badge_class = 'primary';
            } elseif ($item->status_jadwal === 'selesai') {
                $item->status_perjalanan_view = 'Selesai';
                $item->status_badge_class = 'success';
            } elseif ($item->status_jadwal === 'dibatalkan') {
                $item->status_perjalanan_view = 'Dibatalkan';
                $item->status_badge_class = 'danger';
            } else {
                $item->status_perjalanan_view = ucfirst($item->status_jadwal);
                $item->status_badge_class = 'secondary';
            }
            return $item;
        });

        return view('admin.keberangkatan.index', compact('keberangkatan', 'search', 'showEntries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buses = Bus::orderBy('nama_bus')->pluck('nama_bus', 'id'); // Ambil daftar bus untuk dropdown
        $statuses = ['aktif' => 'Aktif', 'dibatalkan' => 'Dibatalkan', 'selesai' => 'Selesai']; // Status Jadwal
        return view('admin.keberangkatan.create', compact('buses', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all()); // Debugging: Tampilkan semua data yang diterima
        $validatedData = $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tanggal_berangkat' => 'required|date',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_sampai' => 'required|date_format:H:i|after:jam_berangkat',
            'durasi_perjalanan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'jumlah_kursi_tersedia' => 'required|integer|min:0',
            'status_jadwal' => 'required|in:aktif,dibatalkan,selesai',
        ]);

        JadwalKeberangkatan::create($validatedData);

        return redirect()->route('admin.keberangkatan.index')->with('success', 'Jadwal keberangkatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalKeberangkatan $keberangkatan)
    {
        // Biasanya tidak digunakan untuk admin panel index, tapi bisa untuk detail view
        return view('admin.keberangkatan.show', compact('keberangkatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalKeberangkatan $keberangkatan)
    {
        $buses = Bus::orderBy('nama_bus')->pluck('nama_bus', 'id');
        $statuses = ['aktif' => 'Aktif', 'dibatalkan' => 'Dibatalkan', 'selesai' => 'Selesai'];
        return view('admin.keberangkatan.edit', compact('keberangkatan', 'buses', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalKeberangkatan $keberangkatan)
    {
        $validatedData = $request->validate([
            'bus_id' => 'required|exists:bus,id',
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tanggal_berangkat' => 'required|date',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_sampai' => 'required|date_format:H:i|after:jam_berangkat',
            'durasi_perjalanan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'jumlah_kursi_tersedia' => 'required|integer|min:0',
            'status_jadwal' => 'required|in:aktif,dibatalkan,selesai',
        ]);

        $keberangkatan->update($validatedData);

        return redirect()->route('admin.keberangkatan.index')->with('success', 'Jadwal keberangkatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalKeberangkatan $keberangkatan)
    {
        $keberangkatan->delete();
        return redirect()->route('admin.keberangkatan.index')->with('success', 'Jadwal keberangkatan berhasil dihapus.');
    }
}
