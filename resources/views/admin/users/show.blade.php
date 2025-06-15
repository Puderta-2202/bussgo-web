@extends('layouts.admin') 

@section('title', 'Detail Pengguna')
@section('page_title', 'Detail Pengguna')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Informasi Pengguna: {{ $user->nama_lengkap }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">ID Pengguna</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>{{ $user->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>No Handphone</th>
                                <td>{{ $user->no_handphone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $user->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Saldo BusPay</th>
                                <td class="font-weight-bold">Rp{{ number_format($user->saldo, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Terdaftar</th>
                                <td>{{ $user->created_at->translatedFormat('l, d F Y, H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4 text-center">
                    {{-- Anda bisa menambahkan foto profil di sini jika ada --}}
                    <i class="fas fa-user-circle fa-10x text-gray-300"></i>
                    <h5 class="mt-3">{{ $user->nama_lengkap }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection