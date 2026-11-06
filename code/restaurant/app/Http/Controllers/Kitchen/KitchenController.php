<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;

class KitchenController extends Controller
{
    private function kitchenId()
    {
        $kitchenId = auth()->user()->kitchen_id;

        if (!$kitchenId) {
            abort(403, 'Akun ini belum terhubung ke dapur.');
        }

        return $kitchenId;
    }

    public function dashboard()
    {
        $kitchenId = $this->kitchenId();

        $items = OrderDetail::where('kitchen_id', $kitchenId);

        return response()->json([
            'message' => 'Dashboard dapur berhasil diambil',
            'data' => [
                'kitchen_id' => $kitchenId,

                'orders_waiting' => (clone $items)
                    ->where('kitchen_status', 'menunggu')
                    ->distinct('order_id')
                    ->count('order_id'),

                'orders_processing' => (clone $items)
                    ->where('kitchen_status', 'diproses')
                    ->distinct('order_id')
                    ->count('order_id'),

                'items_waiting' => (clone $items)
                    ->where('kitchen_status', 'menunggu')
                    ->count(),

                'items_processing' => (clone $items)
                    ->where('kitchen_status', 'diproses')
                    ->count(),

                'items_completed_today' => (clone $items)
                    ->where('kitchen_status', 'selesai')
                    ->whereDate('updated_at', today())
                    ->count(),
            ],
        ]);
    }

    public function orders()
    {
        $kitchenId = $this->kitchenId();

        $orders = Order::whereHas('orderDetails', function ($query) use ($kitchenId) {
            $query->where('kitchen_id', $kitchenId);
        })
            ->with([
                'user:id,name,username',
                'orderDetails' => function ($query) use ($kitchenId) {
                    $query->where('kitchen_id', $kitchenId)
                        ->with('menu:id,name,description,price');
                },
            ])
            ->whereNotIn('status', ['batal', 'selesai'])
            ->orderBy('order_date')
            ->get();

        return response()->json([
            'message' => 'Daftar pesanan dapur berhasil diambil',
            'data' => $orders,
        ]);
    }

    public function show(Order $order)
    {
        $kitchenId = $this->kitchenId();

        $order->load([
            'user:id,name,username',
            'orderDetails' => function ($query) use ($kitchenId) {
                $query->where('kitchen_id', $kitchenId)
                    ->with('menu:id,name,description,price');
            },
        ]);

        if ($order->orderDetails->isEmpty()) {
            abort(404, 'Pesanan tidak ditemukan di dapur ini.');
        }

        return response()->json([
            'message' => 'Detail pesanan dapur berhasil diambil',
            'data' => $order,
        ]);
    }
}
