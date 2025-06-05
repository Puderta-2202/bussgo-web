@extends('layouts.admin')

@section('title', 'Manajemen Bus')
@section('page_title', 'Data Bus')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Bus</h5>
        <a href="{{ route('admin.bus.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Data
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <form action="{{ route('admin.bus.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari bus..." value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-md-8 d-flex justify-content-end align-items-center">
                 <form action="{{ route('admin.bus.index') }}" method="GET" class="d-inline-flex align-items-center">
                    <input type="hidden" name="search" value="{{ $search ?? '' }}">
                    <label for="per_page" class="form-label me-2 mb-0">Show:</label>
                    <select id="per_page" name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="ms-2">entries</span>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th scope="col" style="width: 50px;">No</th>
                        <th scope="col">Nama Bus</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Plat Nomor</th>
                        {{-- <th scope="col">Gambar</th> --}}
                        <th scope="col" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($buses as $index => $bus)
                        <tr>
                            <td>{{ $buses->firstItem() + $index }}</td>
                            <td>{{ $bus->nama_bus }}</td>
                            <td>{{ $bus->jenis_bus }}</td>
                            <td>{{ $bus->plat_nomor }}</td>
                            {{-- <td>Gambar Placeholder</td> --}}
                            <td>
                                <a href="{{ route('admin.bus.edit', $bus->id) }}" class="btn btn-info btn-sm me-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.bus.destroy', $bus->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete-custom" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data bus ini: {{ $bus->nama_bus }}?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data bus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-light">
        @if ($buses->hasPages())
            <div class="d-flex justify-content-center">
                {{ $buses->links('pagination::bootstrap-5') }} {{-- Menggunakan view paginasi Bootstrap 5 --}}
            </div>
        @endif
    </div>
</div>
@endsection