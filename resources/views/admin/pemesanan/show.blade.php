@extends('layouts.admin') {{-- Sesuaikan layout Anda --}}

@section('title', 'Detail Pemesanan - ' . ($pemesanan->kode_booking ?? $pemesanan->id) )

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pemesanan</h1>
        <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Pemesanan: {{ $pemesanan->kode_booking ?? $pemesanan->id }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Data Pemesan:</h5>
                    <table class="table table-sm table-borderless">
                        <tr><th width="40%">Nama Pemesan</th><td>: {{ $pemesanan->nama_pemesan }}</td></tr>
                        <tr><th>Email</th><td>: {{ $pemesanan->email_pemesan ?? '-' }}</td></tr>
                        <tr><th>Telepon</th><td>: {{ $pemesanan->telepon_pemesan ?? '-' }}</td></tr>
                        <tr><th>User Akun</th><td>: {{ $pemesanan->user ? $pemesanan->user->name . ' (ID: '. $pemesanan->user_id .')' : 'Tamu' }}</td></tr>
                    </table>

                    <h5>Detail Pembayaran:</h5>
                    <table class="table table-sm table-borderless">
                        <tr><th width="40%">Jumlah Tiket</th><td>: {{ $pemesanan->jumlah_tiket }}</td></tr>
                        <tr><th>Total Harga</th><td>: Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td></tr>
                        <tr><th>Metode Pembayaran</th><td>: {{ $pemesanan->metode_pembayaran ?? '-' }}</td></tr>
                        <tr><th>Status Pembayaran</th>
                            <td>
                                @php
                                    $badgeClass = 'secondary';
                                    if ($pemesanan->status_pembayaran === 'berhasil') $badgeClass = 'success';
                                    elseif (in_array($pemesanan->status_pembayaran, ['gagal', 'dibatalkan'])) $badgeClass = 'danger';
                                    elseif ($pemesanan->status_pembayaran === 'pending') $badgeClass = 'warning';
                                    elseif ($pemesanan->status_pembayaran === 'kadaluarsa') $badgeClass = 'dark';
                                @endphp
                                <span class="badge badge-{{ $badgeClass }} text-capitalize">{{ str_replace('_', ' ', $pemesanan->status_pembayaran) }}</span>
                            </td>
                        </tr>
                         <tr><th>Tanggal Pemesanan</th><td>: {{ \Carbon\Carbon::parse($pemesanan->created_at)->translatedFormat('l, d F Y, H:i:s') }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Detail Perjalanan:</h5>
                    @if($pemesanan->jadwalKeberangkatan)
                        <table class="table table-sm table-borderless">
                            <tr><th width="40%">Nama Bus</th><td>: {{ $pemesanan->jadwalKeberangkatan->bus ? $pemesanan->jadwalKeberangkatan->bus->nama_bus : 'N/A' }}</td></tr>
                            <tr><th>Jenis Bus</th><td>: {{ $pemesanan->jadwalKeberangkatan->bus ? $pemesanan->jadwalKeberangkatan->bus->jenis_bus : 'N/A' }}</td></tr>
                            <tr><th>Plat Nomor</th><td>: {{ $pemesanan->jadwalKeberangkatan->bus ? $pemesanan->jadwalKeberangkatan->bus->plat_nomor : 'N/A' }}</td></tr>
                            <tr><th>Rute</th><td>: {{ $pemesanan->jadwalKeberangkatan->asal }} <i class="fas fa-arrow-right fa-xs"></i> {{ $pemesanan->jadwalKeberangkatan->tujuan }}</td></tr>
                            <tr><th>Tgl. Berangkat</th><td>: {{ \Carbon\Carbon::parse($pemesanan->jadwalKeberangkatan->tanggal_berangkat)->translatedFormat('l, d F Y') }}</td></tr>
                            <tr><th>Jam Berangkat</th><td>: {{ \Carbon\Carbon::parse($pemesanan->jadwalKeberangkatan->jam_berangkat)->format('H:i') }}</td></tr>
                            <tr><th>Jam Sampai (Estimasi)</th><td>: {{ \Carbon\Carbon::parse($pemesanan->jadwalKeberangkatan->jam_sampai)->format('H:i') }}</td></tr>
                            <tr><th>Durasi</th><td>: {{ $pemesanan->jadwalKeberangkatan->durasi_perjalanan }}</td></tr>
                        </table>
                    @else
                        <p class="text-danger">Informasi jadwal keberangkatan tidak tersedia (mungkin telah dihapus).</p>
                    @endif
                </div>
            </div>
             <hr>
            <div class="text-right">
                <a href="{{ route('admin.pemesanan.cetak', $pemesanan->id) }}" class="btn btn-primary" target="_blank"><i class="fas fa-print"></i> Cetak Bukti</a>
            </div>
        </div>
    </div>
</div>
@endsection