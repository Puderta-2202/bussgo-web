@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="fas fa-exclamation-triangle"></i> Perhatian!</strong>
        <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="bus_id">Pilih Bus <span class="text-danger">*</span></label>
            <select name="bus_id" id="bus_id" class="form-control @error('bus_id') is-invalid @enderror" required>
                <option value="" disabled {{ old('bus_id') ? '' : 'selected' }}>-- Pilih Bus --</option>
                @foreach($buses as $id => $nama_bus)
                    <option value="{{ $id }}" {{ (isset($keberangkatan) && $keberangkatan->bus_id == $id) || old('bus_id') == $id ? 'selected' : '' }}>
                        {{ $nama_bus }}
                    </option>
                @endforeach
            </select>
            @error('bus_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
     <div class="col-md-6">
        <div class="form-group">
            <label for="status_jadwal">Status Jadwal <span class="text-danger">*</span></label>
            <select name="status_jadwal" id="status_jadwal" class="form-control @error('status_jadwal') is-invalid @enderror" required>
                <option value="" disabled {{ old('status_jadwal') ? '' : 'selected' }}>-- Pilih Status --</option>
                @foreach($statuses as $key => $value)
                    <option value="{{ $key }}" {{ (isset($keberangkatan) && $keberangkatan->status_jadwal == $key) || old('status_jadwal') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('status_jadwal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="asal">Kota Asal <span class="text-danger">*</span></label>
            <input type="text" name="asal" id="asal" class="form-control @error('asal') is-invalid @enderror" value="{{ isset($keberangkatan) ? $keberangkatan->asal : old('asal') }}" placeholder="Masukkan kota asal" required>
            @error('asal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="tujuan">Kota Tujuan <span class="text-danger">*</span></label>
            <input type="text" name="tujuan" id="tujuan" class="form-control @error('tujuan') is-invalid @enderror" value="{{ isset($keberangkatan) ? $keberangkatan->tujuan : old('tujuan') }}" placeholder="Masukkan kota tujuan" required>
            @error('tujuan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="tanggal_berangkat">Tanggal Berangkat <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_berangkat" id="tanggal_berangkat" class="form-control @error('tanggal_berangkat') is-invalid @enderror" value="{{ isset($keberangkatan) && $keberangkatan->tanggal_berangkat ? $keberangkatan->tanggal_berangkat->format('Y-m-d') : old('tanggal_berangkat') }}" required>
            @error('tanggal_berangkat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="jam_berangkat">Jam Berangkat <span class="text-danger">*</span></label>
            <input type="time" name="jam_berangkat" id="jam_berangkat" class="form-control @error('jam_berangkat') is-invalid @enderror" value="{{ isset($keberangkatan) ? $keberangkatan->jam_berangkat : old('jam_berangkat') }}" required>
            @error('jam_berangkat')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="jam_sampai">Perkiraan Jam Sampai <span class="text-danger">*</span></label>
            <input type="time" name="jam_sampai" id="jam_sampai" class="form-control @error('jam_sampai') is-invalid @enderror" value="{{ isset($keberangkatan) ? $keberangkatan->jam_sampai : old('jam_sampai') }}" required>
            @error('jam_sampai')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="durasi_perjalanan">Estimasi Durasi Perjalanan <span class="text-danger">*</span></label>
            <input type="text" name="durasi_perjalanan" id="durasi_perjalanan" class="form-control @error('durasi_perjalanan') is-invalid @enderror" value="{{ isset($keberangkatan) ? $keberangkatan->durasi_perjalanan : old('durasi_perjalanan') }}" placeholder="Contoh: 8 Jam 30 Menit" required>
            @error('durasi_perjalanan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="harga">Harga Tiket (Rp) <span class="text-danger">*</span></label>
            <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ isset($keberangkatan) ? $keberangkatan->harga : old('harga') }}" step="1000" min="0" placeholder="Contoh: 150000" required>
            @error('harga')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="jumlah_kursi_tersedia">Jumlah Kursi Tersedia <span class="text-danger">*</span></label>
            <input type="number" name="jumlah_kursi_tersedia" id="jumlah_kursi_tersedia" class="form-control @error('jumlah_kursi_tersedia') is-invalid @enderror" value="{{ isset($keberangkatan) ? $keberangkatan->jumlah_kursi_tersedia : old('jumlah_kursi_tersedia') }}" min="0" placeholder="Contoh: 40" required>
            @error('jumlah_kursi_tersedia')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group mt-4 text-right">
    <a href="{{ route('admin.keberangkatan.index') }}" class="btn btn-outline-secondary mr-2">
        <i class="fas fa-times"></i> Batal
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> {{ isset($keberangkatan) ? 'Update Jadwal' : 'Simpan Jadwal' }}
    </button>
</div>