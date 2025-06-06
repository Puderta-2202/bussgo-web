@extends('layouts.admin')
@section('title', 'Manajemen Top Up')
@section('page_title', 'Permintaan Top Up Saldo')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary">
        <h6 class="m-0 font-weight-bold text-white">Daftar Permintaan Top Up</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Tgl. Request</th>
                        <th>Nama User</th>
                        <th>Jumlah</th>
                        <th>Bukti Transfer</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                        <tr>
                            <td>{{ $request->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td>{{ $request->user->name }}</td>
                            <td>Rp{{ number_format($request->amount, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ asset('storage/' . str_replace('public/', '', $request->proof_of_payment)) }}" target="_blank" class="btn btn-sm btn-info">
                                    Lihat Bukti
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'success' ? 'success' : 'danger') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>
                                @if ($request->status == 'pending')
                                    <form action="{{ route('admin.topup.approve', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.topup.reject', $request->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
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
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection