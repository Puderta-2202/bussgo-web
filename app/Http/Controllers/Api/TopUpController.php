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
        ]);

        $user = Auth::user();

        TopUpTransaction::create([
            'user_id' => $user->id,
            'order_id' => 'TOPUP-' . $user->id . '-' . time(),
            'amount' => $validatedData['amount'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Permintaan top up berhasil dikirim dan sedang menunggu persetujuan admin.'
        ], 201);
    }
}
