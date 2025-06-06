<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopUpTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TopUpManagementController extends Controller
{
    public function index()
    {
        // Tampilkan semua request top up, utamakan yang pending
        $requests = TopUpTransaction::with('user')
            ->orderByRaw("FIELD(status, 'pending') DESC")
            ->latest()
            ->paginate(15);
        return view('admin.topup.index', compact('requests'));
    }

    public function approve(TopUpTransaction $transaction)
    {
        // Pastikan hanya transaksi pending yang bisa di-approve
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }

        // Gunakan transaction untuk memastikan saldo bertambah & status berubah bersamaan
        DB::transaction(function () use ($transaction) {
            $user = User::find($transaction->user_id);
            $user->increment('saldo', $transaction->amount);
            $transaction->update(['status' => 'success']);
        });

        return back()->with('success', 'Top up berhasil disetujui.');
    }

    public function reject(TopUpTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
        }
        $transaction->update(['status' => 'rejected']);
        return back()->with('success', 'Top up berhasil ditolak.');
    }
}
