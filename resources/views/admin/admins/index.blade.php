@extends('layouts.admin') {{-- Sesuaikan dengan layout master admin Anda --}}

@section('title', 'Manajemen Admin Sistem')
@section('page_title', 'Data Admin Sistem')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Admin Sistem</h5>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-user-plus me-1"></i> Tambah Admin
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <form action="{{ route('admin.admins.index') }}" method="GET">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-md-8 d-flex justify-content-end align-items-center">
                 <form action="{{ route('admin.admins.index') }}" method="GET" class="d-inline-flex align-items-center">
                    <input type="hidden" name="search" value="{{ $search ?? '' }}">
                    <label for="per_page" class="form-label me-2 mb-0 small">Tampilkan:</label>
                    <select id="per_page" name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="ms-2 small">entri</span>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th scope="col" style="width: 50px;">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Email</th>
                        <th scope="col" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $index => $admin)
                        <tr>
                            <td>{{ $admins->firstItem() + $index }}</td>
                            <td>{{ $admin->nama }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-info btn-sm me-1" title="Edit">
                                    <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Edit</span>
                                </a>
                                @if(Auth::guard('admin_sistem')->id() != $admin->id && $admins->total() > 1) {{-- Jangan hapus diri sendiri & jika bukan admin terakhir --}}
                                <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete-custom" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus admin {{ $admin->nama }}?')">
                                        <i class="fas fa-trash"></i> <span class="d-none d-md-inline">Hapus</span>
                                    </button>
                                </form>
                                @else
                                    <button type="button" class="btn btn-danger btn-sm btn-delete-custom" disabled title="Tidak dapat dihapus">
                                        <i class="fas fa-trash"></i> <span class="d-none d-md-inline">Hapus</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data admin sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($admins->hasPages())
    <div class="card-footer bg-light">
            <div class="d-flex justify-content-center">
                {{ $admins->links('pagination::bootstrap-5') }}
            </div>
    </div>
    @endif
</div>
@endsection