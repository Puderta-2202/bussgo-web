@extends('layouts.admin') 

@section('title', 'Manajemen Top Up')
@section('page_title', 'Permintaan Top Up Saldo')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Daftar Permintaan Top Up</h6>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Tanggal Request</th>
                        <th>Nama User</th>
                        <th>Jumlah Top Up</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                        <tr>
                            <td>{{ $request->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td>{{ $request->user->nama_lengkap ?? 'User Dihapus' }}</td>
                            <td>Rp{{ number_format($request->amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $badgeClass = 'secondary';
                                    if ($request->status == 'success') $badgeClass = 'success';
                                    elseif ($request->status == 'rejected') $badgeClass = 'danger';
                                    elseif ($request->status == 'pending') $badgeClass = 'warning';
                                @endphp
                                <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($request->status) }}</span>
                            </td>
                            <td>
                                @if ($request->status == 'pending')
                                    <form action="{{ route('admin.topup.approve', $request->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menyetujui top up ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.topup.reject', $request->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menolak top up ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Tidak ada permintaan top up.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection