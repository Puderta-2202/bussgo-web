@extends('layouts.admin') {{-- Sesuaikan dengan nama file layout utama Anda --}}

@section('title', 'Data Pengguna')
@section('page_title', 'Daftar Pengguna')

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-white">Daftar Pengguna</h6>
            {{-- Tombol Tambah Data bisa diaktifkan jika admin perlu menambah user manual --}}
            {{-- <a href="#" class="btn btn-light btn-sm">
                <i class="fas fa-plus fa-sm"></i> Tambah Pengguna
            </a> --}}
        </div>
        
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('admin.users.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari pengguna..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 d-flex justify-content-end">
                     {{-- Fitur "Show Entries" bisa ditambahkan di sini jika diperlukan --}}
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableUsers" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>No Handphone</th>
                            <th>Saldo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->nama_lengkap }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->no_handphone ?? '-' }}</td>
                            <td>Rp{{ number_format($user->saldo, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                {{-- Tombol Edit (Biru seperti di contoh) --}}
                                <a href="#" class="btn btn-primary btn-sm" title="Edit Pengguna">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- Tombol Hapus (Merah) --}}
                                <a href="#" class="btn btn-danger btn-sm" title="Hapus Pengguna" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pengguna yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $users->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection