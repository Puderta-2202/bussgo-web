<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus; // Pastikan model Bus diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Untuk validasi unique saat update

class BusController extends Controller
{
    /**
     * Menampilkan daftar semua bus.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default 10 entri per halaman

        $query = Bus::orderBy('created_at', 'desc');

        if ($search) {
            $query->where('nama_bus', 'like', "%{$search}%")
                ->orWhere('jenis_bus', 'like', "%{$search}%")
                ->orWhere('plat_nomor', 'like', "%{$search}%");
        }

        $buses = $query->paginate($perPage)->withQueryString(); // withQueryString agar parameter search & per_page tetap ada di link paginasi

        return view('admin.bus.index', compact('buses', 'search', 'perPage'));
    }

    /**
     * Menampilkan form untuk membuat bus baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.bus.create');
    }

    /**
     * Menyimpan bus baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_bus' => 'required|string|max:100|unique:bus,nama_bus',
            'jenis_bus' => 'required|string|max:50', // Sesuaikan max length jika perlu
            'plat_nomor' => 'required|string|max:20|unique:bus,plat_nomor',
            // 'jumlah_kursi' => 'nullable|integer|min:1', // Jika Anda menambahkan kolom ini
        ]);

        Bus::create($validatedData);

        return redirect()->route('admin.bus.index')->with('success', 'Data bus berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data bus.
     * Route-model binding akan otomatis mengambil instance Bus berdasarkan ID.
     *
     * @param  \App\Models\Bus  $bus
     * @return \Illuminate\View\View
     */
    public function edit(Bus $bus)
    {
        return view('admin.bus.edit', compact('bus'));
    }

    /**
     * Memperbarui data bus di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bus  $bus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Bus $bus)
    {
        $validatedData = $request->validate([
            'nama_bus' => [
                'required',
                'string',
                'max:100',
                Rule::unique('bus', 'nama_bus')->ignore($bus->id),
            ],
            'jenis_bus' => 'required|string|max:50',
            'plat_nomor' => [
                'required',
                'string',
                'max:20',
                Rule::unique('bus', 'plat_nomor')->ignore($bus->id),
            ],
            // 'jumlah_kursi' => 'nullable|integer|min:1',
        ]);

        $bus->update($validatedData);

        return redirect()->route('admin.bus.index')->with('success', 'Data bus berhasil diperbarui.');
    }

    /**
     * Menghapus data bus dari database.
     *
     * @param  \App\Models\Bus  $bus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Bus $bus)
    {
        // Pengecekan apakah bus masih terkait dengan jadwal keberangkatan
        if ($bus->jadwalKeberangkatans()->exists()) { // Pastikan relasi jadwalKeberangkatans() ada di model Bus
            return redirect()->route('admin.bus.index')->with('error', 'Gagal menghapus! Bus masih digunakan di jadwal keberangkatan.');
        }

        try {
            $bus->delete();
            return redirect()->route('admin.bus.index')->with('success', 'Data bus berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani error umum jika ada foreign key constraint lain yang mencegah penghapusan
            return redirect()->route('admin.bus.index')->with('error', 'Gagal menghapus data bus karena masih terkait dengan data lain atau terjadi kesalahan database.');
        }
    }
}
