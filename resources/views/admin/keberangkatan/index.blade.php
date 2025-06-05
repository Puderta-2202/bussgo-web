@extends('layouts.admin') {{-- Sesuaikan dengan layout utama admin Anda --}}

@section('title', 'Jadwal Keberangkatan')
@section('page_title', 'Jadwal Keberangkatan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('admin.keberangkatan.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Jadwal
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="m-0 font-weight-bold text-primary">Data Jadwal Keberangkatan</h6>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('admin.keberangkatan.index') }}" method="GET" class="form-inline float-md-right">
                        <div class="form-group mr-2 mb-2 mb-md-0">
                            <select name="show" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('show') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTableKeberangkatan" width="100%" cellspacing="0">
                    <thead class="thead-light"> {{-- atau thead-dark sesuai preferensi --}}
                        <tr>
                            <th>No</th>
                            <th>Tanggal & Jam Berangkat</th>
                            <th>Nama Bus</th>
                            {{-- <th>Gambar</th> --}}
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
                            {{-- <td>
                                @if($item->bus && $item->bus->gambar_bus)
                                    <img src="{{ asset('storage/' . $item->bus->gambar_bus) }}" alt="{{ $item->bus->nama_bus }}" width="70" class="img-thumbnail">
                                @else
                                    <img src="{{ asset('img/default_bus_thumbnail.png') }}" alt="No Image" width="70" class="img-thumbnail"> {{-- Ganti path default --}}
                                {{-- @endif --}}
                            {{-- </td> --}} 
                            <td>{{ $item->asal }} <i class="fas fa-long-arrow-alt-right fa-xs d-none d-md-inline"></i><span class="d-md-none"><br></span> {{ $item->tujuan }}</td>
                            <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>{{ $item->jumlah_kursi_tersedia }}</td>
                            <td>
                                <span class="badge badge-pill badge-{{ $item->status_badge_class ?? 'secondary' }}">
                                    {{ $item->status_perjalanan_view ?? ucfirst($item->status_jadwal) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.keberangkatan.edit', $item->id) }}" class="btn btn-warning btn-sm mb-1" title="Edit">
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
                            <td colspan="9" class="text-center">
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
                {{ $keberangkatan->appends(request()->except('page'))->links() }} {{-- Bootstrap 4 pagination --}}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection