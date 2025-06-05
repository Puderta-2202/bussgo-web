@extends('layouts.admin') {{-- Sesuaikan dengan layout utama admin Anda --}}

@section('title', 'Edit Jadwal Keberangkatan')
@section('page_title', 'Edit Jadwal Keberangkatan')

@section('content')
<div class="container-fluid">
    <p class="mb-4">Formulir untuk mengedit jadwal keberangkatan.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Jadwal Keberangkatan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.keberangkatan.update', $keberangkatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.keberangkatan._form', ['keberangkatan' => $keberangkatan])
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