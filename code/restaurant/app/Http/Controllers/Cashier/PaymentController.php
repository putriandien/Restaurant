<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card,transfer,qris',
        ]);

        $payment = DB::transaction(function () use (
            $order,
            $validated
        ) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status === 'batal') {
                abort(422, 'Pesanan yang dibatalkan tidak dapat dibayar.');
            }

            if ($lockedOrder->payment()->exists()) {
                abort(422, 'Pesanan ini sudah memiliki pembayaran.');
            }

            return Payment::create([
                'order_id' => $lockedOrder->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $lockedOrder->total_amount,
                'payment_time' => now(),
                'status' => 'berhasil',
            ]);
        });

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'PAYMENT_SUCCESS',
            'description' => 'Pembayaran pesanan '
                . $order->order_number
                . ' sebesar Rp'
                . number_format((float) $payment->amount, 0, ',', '.')
                . ' melalui '
                . strtoupper($payment->payment_method),
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Pembayaran berhasil',
            'data' => $payment->load('order'),
        ], 201);
    }
}