@extends('layouts.admin') {{-- Sesuaikan dengan layout utama admin Anda --}}

@section('title', 'Tambah Jadwal Keberangkatan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Tambah Jadwal Keberangkatan</h1>
    <p class="mb-4">Formulir untuk menambahkan jadwal keberangkatan baru.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Jadwal Keberangkatan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.keberangkatan.store') }}" method="POST">
                @csrf
                @include('admin.keberangkatan._form')
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
{{-- Jika ada CSS tambahan khusus halaman ini --}}
@endpush

@push('scripts')
{{-- Jika ada JS tambahan khusus halaman ini, misal untuk datepicker --}}
@endpush