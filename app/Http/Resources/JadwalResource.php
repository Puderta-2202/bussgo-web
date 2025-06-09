<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'asal' => $this->asal,
            'tujuan' => $this->tujuan,
            'tanggal_berangkat' => $this->tanggal_berangkat->format('Y-m-d'),
            'jam_berangkat' => date('H:i', strtotime($this->jam_berangkat)),
            'jam_sampai' => date('H:i', strtotime($this->jam_sampai)),
            'durasi_perjalanan' => $this->durasi_perjalanan,
            'harga' => (int) $this->harga,
            'kursi_tersedia' => $this->jumlah_kursi_tersedia,
            'bus' => [ // Kita sisipkan informasi bus yang relevan
                'nama' => $this->whenLoaded('bus', $this->bus->nama_bus),
                'jenis' => $this->whenLoaded('bus', $this->bus->jenis_bus),
                'plat_nomor' => $this->whenLoaded('bus', $this->bus->plat_nomor),
                'kapasitas_kursi' => $this->whenLoaded('bus', $this->bus->kapasitas_kursi),
            ]
        ];
    }
}
