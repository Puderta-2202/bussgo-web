@extends('layouts.admin') 

@section('title', 'Jadwal Keberangkatan')
@section('page_title', 'Jadwal Keberangkatan')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-white">Data Jadwal Keberangkatan</h6>
            <a href="{{ route('admin.keberangkatan.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus fa-sm"></i> Tambah Jadwal
            </a>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.keberangkatan.index') }}" method="GET">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari rute, bus..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <div class="d-flex align-items-center">
                            <label for="show" class="mr-2 mb-0">Tampilkan:</label>
                            <select name="show" id="show" class="form-control form-control-sm" style="width: auto;" onchange="this.form.submit()">
                                <option value="10" {{ request('show', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('show', 25) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('show', 50) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('show', 100) == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="ml-2">entri</span>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTableKeberangkatan" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal & Jam Berangkat</th>
                            <th>Nama Bus</th>
                            <th>Rute (Asal <i class="fas fa-long-arrow-alt-right fa-xs"></i> Tujuan)</th>
                            <th>Harga</th>
                            <th>Kursi</th>
                            <th>Status Perjalanan</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keberangkatan as $index => $item)
                        <tr>
                            <td>{{ $keberangkatan->firstItem() + $index }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal_berangkat)->translatedFormat('l, d M Y') }}<br>
                                <small class="text-muted">Jam {{ \Carbon\Carbon::parse($item->jam_berangkat)->format('H:i') }}</small>
                            </td>
                            <td>{{ $item->bus ? $item->bus->nama_bus : 'N/A' }}</td>
                            <td>{{ $item->asal }} <i class="fas fa-long-arrow-alt-right fa-xs d-none d-md-inline"></i><span class="d-md-none"><br></span> {{ $item->tujuan }}</td>
                            <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>{{ $item->jumlah_kursi_tersedia }}</td>
                            <td>
                                <span class="badge badge-pill badge-{{ $item->status_badge_class ?? 'secondary' }}">
                                    {{ $item->status_perjalanan_view ?? ucfirst($item->status_jadwal) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.keberangkatan.edit', $item->id) }}" class="btn btn-primary btn-sm mb-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.keberangkatan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mb-1" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            {{-- Colspan disesuaikan menjadi 8 karena kolom Gambar di-comment --}}
                            <td colspan="8" class="text-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> Tidak ada data jadwal keberangkatan yang ditemukan.
                                @if(request('search'))
                                    <br><small>Coba ubah kata kunci pencarian Anda.</small>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($keberangkatan->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{-- Menggunakan query() agar semua filter (search & show) terbawa saat pindah halaman --}}
                {{ $keberangkatan->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection