@extends('layouts.admin')

@section('title', 'Edit Bus')
@section('page_title', 'Edit Data Bus')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Edit Bus: {{ $bus->nama_bus }}</h5>
            </div>
            {{-- PERBAIKAN: Tambahkan $bus->id di dalam route() --}}
            <form action="{{ route('admin.bus.update', $bus->id) }}" method="POST">
                @csrf
                @method('PUT') 
                
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nama_bus" class="form-label">Nama Bus <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_bus') is-invalid @enderror" id="nama_bus" name="nama_bus" value="{{ old('nama_bus', $bus->nama_bus) }}" required>
                        @error('nama_bus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jenis_bus" class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('jenis_bus') is-invalid @enderror" id="jenis_bus" name="jenis_bus" value="{{ old('jenis_bus', $bus->jenis_bus) }}" required>
                        @error('jenis_bus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="plat_nomor" class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('plat_nomor') is-invalid @enderror" id="plat_nomor" name="plat_nomor" value="{{ old('plat_nomor', $bus->plat_nomor) }}" required>
                        @error('plat_nomor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- TAMBAHAN: Input untuk kapasitas kursi --}}
                    <div class="mb-3">
                        <label for="kapasitas_kursi" class="form-label">Kapasitas Kursi <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('kapasitas_kursi') is-invalid @enderror" id="kapasitas_kursi" name="kapasitas_kursi" value="{{ old('kapasitas_kursi', $bus->kapasitas_kursi) }}" required min="1">
                        @error('kapasitas_kursi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="card-footer bg-light text-end">
                    <a href="{{ route('admin.bus.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection