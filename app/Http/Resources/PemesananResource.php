<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PemesananResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_pemesanan' => $this->id,
            'kode_booking' => $this->kode_booking,
            'nama_pemesan' => $this->nama_pemesan,
            'jumlah_tiket' => $this->jumlah_tiket,
            'total_harga' => (int) $this->total_harga,
            'status_pembayaran' => $this->status_pembayaran,
            'metode_pembayaran' => $this->metode_pembayaran,
            'tanggal_pemesanan' => $this->created_at->format('Y-m-d H:i:s'),
            // Memuat detail jadwal menggunakan JadwalResource yang sudah kita buat
            'jadwal_keberangkatan' => new JadwalResource($this->whenLoaded('jadwalKeberangkatan')),
        ];
    }
}
