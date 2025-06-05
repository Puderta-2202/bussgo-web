<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pemesanan - {{ $pemesanan->kode_booking ?? $pemesanan->id }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; }
        .container-print { border: 1px solid #ddd; padding: 25px; max-width: 800px; margin: auto; }
        h3 { text-align: center; margin-bottom: 20px; color: #2c3e50; }
        hr { border-top: 1px dashed #ccc; }
        p { margin-bottom: 0.75rem; line-height: 1.6; }
        strong { color: #555; }
        h5 { margin-top: 20px; margin-bottom: 10px; color: #3498db; border-bottom: 1px solid #eee; padding-bottom: 5px;}
        .footer-print { text-align:center; font-size: 0.9em; margin-top: 30px; color: #777; }
        .text-danger { color: #e74c3c; }
        /* Styles for print media */
        @media print {
            body { margin: 0; color: #000; }
            .container-print { border: none; padding: 10px; box-shadow: none; }
            .btn { display: none; } /* Hide buttons on print */
            a { text-decoration: none; color: inherit; }
            h3 { color: #000; }
            h5 { color: #000; border-bottom: 1px solid #000; }
            strong { color: #000; }
        }
    </style>
</head>
<body>
    <div class="container-print">
        <h3>Bukti Pemesanan Tiket Bus</h3>
        <hr>
        <p><strong>Kode Booking:</strong> {{ $pemesanan->kode_booking ?? $pemesanan->id }}</p>
        <p><strong>Tanggal Pemesanan:</strong> {{ \Carbon\Carbon::parse($pemesanan->created_at)->translatedFormat('l, d F Y, H:i') }}</p>
        <hr>
        <h5>Detail Pemesan:</h5>
        <p><strong>Nama:</strong> {{ $pemesanan->nama_pemesan }}</p>
        <p><strong>Email:</strong> {{ $pemesanan->email_pemesan ?? '-' }}</p>
        <p><strong>Telepon:</strong> {{ $pemesanan->telepon_pemesan ?? '-' }}</p>
        <hr>
        <h5>Detail Perjalanan:</h5>
        @if($pemesanan->jadwalKeberangkatan)
            <p><strong>Bus:</strong> {{ $pemesanan->jadwalKeberangkatan->bus ? $pemesanan->jadwalKeberangkatan->bus->nama_bus : 'N/A' }} ({{ $pemesanan->jadwalKeberangkatan->bus ? $pemesanan->jadwalKeberangkatan->bus->plat_nomor : 'N/A' }})</p>
            <p><strong>Rute:</strong> {{ $pemesanan->jadwalKeberangkatan->asal }} &rarr; {{ $pemesanan->jadwalKeberangkatan->tujuan }}</p>
            <p><strong>Tanggal Berangkat:</strong> {{ \Carbon\Carbon::parse($pemesanan->jadwalKeberangkatan->tanggal_berangkat)->translatedFormat('l, d F Y') }}</p>
            <p><strong>Jam Berangkat:</strong> Pukul {{ \Carbon\Carbon::parse($pemesanan->jadwalKeberangkatan->jam_berangkat)->format('H:i') }}</p>
        @else
            <p class="text-danger">Data perjalanan tidak tersedia.</p>
        @endif
        <hr>
        <h5>Detail Pembayaran:</h5>
        <p><strong>Jumlah Tiket:</strong> {{ $pemesanan->jumlah_tiket }}</p>
        <p><strong>Total Harga:</strong> Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> <strong style="text-transform: capitalize;">{{ str_replace('_', ' ', $pemesanan->status_pembayaran) }}</strong></p>
        <hr>
        <div class="footer-print">
            <p>Terima kasih telah melakukan pemesanan. Harap tunjukkan bukti ini saat keberangkatan.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'BusGo Web') }}</p>
        </div>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-success"><i class="fas fa-print"></i> Cetak Halaman Ini</button>
    </div>
</body>
</html>