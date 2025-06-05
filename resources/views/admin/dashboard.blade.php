@extends('layouts.admin') {{-- Pastikan ini adalah layout master admin Anda --}}

@section('title', 'Dashboard Admin - BusGO')
@section('page_title', 'Home')

@section('content')
<div class="container-fluid"> {{-- Menggunakan container-fluid agar lebih responsif --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm" style="border-left: 5px solid #0d6efd; border-radius: 0.5rem;"> {{-- Tema biru pada border kiri card --}}
                <div class="card-body p-4"> {{-- Padding lebih besar --}}
                    <h2 class="card-title mb-3" style="color: #0d6efd; font-weight: 600;">Selamat Datang Admin {{ $adminName ?? 'Admin' }}!</h2>
                    <p class="card-text text-muted" style="font-size: 1.05rem;">
                        Selamat datang di halaman utama administrator BusGO.
                    </p>
                    <p class="card-text text-muted" style="font-size: 1.05rem;">
                        Silakan klik pada pilihan menu di sebelah kiri Anda untuk mengelola data aplikasi.
                    </p>
                    <hr class="my-4">
                    <div class="text-center">
                        <h1 class="display-1 text-muted" style="font-weight: 200; letter-spacing: 1px; opacity: 0.7;">
                            WELCOME
                        </h1>
                        <i class="fas fa-bus fa-3x text-primary mt-3" style="opacity: 0.5;"></i> {{-- Ikon bus sebagai dekorasi --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Jika Anda memiliki CSS kustom khusus untuk halaman ini --}}
<style>
    /* Anda bisa menambahkan styling kustom di sini jika diperlukan,
       namun usahakan memaksimalkan kelas Bootstrap */
    .card-welcome {
        /* Styling tambahan untuk card welcome jika kelas Bootstrap standar tidak cukup */
    }
</style>
@endpush
