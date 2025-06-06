@extends('layouts.admin') 

@section('title', 'Data Pemesanan')

@section('page_title', 'Data Pemesanan Tiket')

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
            <h6 class="m-0 font-weight-bold text-white">Daftar Pemesanan Tiket</h6>
        </div>
        
        <div class="card-body">
            <form action="{{ route('admin.pemesanan.index') }}" method="GET">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari pemesan, user, bus..." value="{{ request('search') }}">
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
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tgl. Pesan</th>
                            <th>Nama Pemesan</th>
                            <th>User</th>
                            <th>Jadwal Berangkat</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemesanan as $index => $item)
                        <tr>
                            <td>{{ $pemesanan->firstItem() + $index }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}</td>
                            <td>{{ $item->nama_pemesan }}</td>
                            <td>{{ $item->user ? $item->user->name : ($item->email_pemesan ?? 'Tamu') }}</td>
                            <td>
                                @if($item->jadwalKeberangkatan)
                                    {{ $item->jadwalKeberangkatan->bus ? $item->jadwalKeberangkatan->bus->nama_bus : 'N/A Bus' }} <br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->jadwalKeberangkatan->tanggal_berangkat)->translatedFormat('d M Y') }},
                                        Jam {{ \Carbon\Carbon::parse($item->jadwalKeberangkatan->jam_berangkat)->format('H:i') }}
                                    </small>
                                @else
                                    Jadwal Dihapus
                                @endif
                            </td>
                            <td>Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $badgeClass = 'secondary';
                                    if ($item->status_pembayaran === 'berhasil') $badgeClass = 'success';
                                    elseif (in_array($item->status_pembayaran, ['gagal', 'dibatalkan'])) $badgeClass = 'danger';
                                    elseif ($item->status_pembayaran === 'pending') $badgeClass = 'warning';
                                    elseif ($item->status_pembayaran === 'kadaluarsa') $badgeClass = 'dark';
                                @endphp
                                <span class="badge badge-{{ $badgeClass }} text-capitalize">{{ str_replace('_', ' ', $item->status_pembayaran) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pemesanan.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.pemesanan.cetak', $item->id) }}" class="btn btn-success btn-sm" title="Cetak Bukti" target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                <form action="{{ route('admin.pemesanan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pemesanan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data pemesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($pemesanan->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $pemesanan->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection