<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TopUpTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TopUpController extends Controller
{
    public function requestTopUp(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:10000',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
        ]);

        $user = Auth::user();
        $orderId = 'TOPUP-' . $user->id . '-' . time();

        // Simpan file gambar bukti transfer
        $path = $request->file('proof_of_payment')->store('public/proofs');

        // Buat catatan transaksi dengan status 'pending'
        TopUpTransaction::create([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => $validatedData['amount'],
            'proof_of_payment' => $path, // Simpan path filenya
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Permintaan top up berhasil dikirim dan sedang menunggu persetujuan admin.'
        ], 201);
    }
}
