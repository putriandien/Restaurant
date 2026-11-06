<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class KitchenOrderController extends Controller
{
    private function kitchenId()
    {
        $kitchenId = auth()->user()->kitchen_id;

        if (!$kitchenId) {
            abort(403, 'Akun ini belum terhubung ke dapur.');
        }

        return $kitchenId;
    }

    public function start(Order $order)
    {
        return $this->changeStatus($order, 'menunggu', 'diproses');
    }

    public function complete(Order $order)
    {
        return $this->changeStatus($order, 'diproses', 'selesai');
    }

    private function changeStatus(
        Order $order,
        string $from,
        string $to
    ) {
        $kitchenId = $this->kitchenId();

        $result = DB::transaction(function () use (
            $order,
            $kitchenId,
            $from,
            $to
        ) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->status === 'batal') {
                abort(422, 'Pesanan yang dibatalkan tidak dapat diproses.');
            }

            $items = OrderDetail::where('order_id', $lockedOrder->id)
                ->where('kitchen_id', $kitchenId)
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                abort(404, 'Tidak ada item pesanan untuk dapur ini.');
            }

            $eligibleItems = $items->where('kitchen_status', $from);

            if ($eligibleItems->isEmpty()) {
                abort(
                    422,
                    $from === 'menunggu'
                        ? 'Tidak ada item yang menunggu untuk diproses.'
                        : 'Tidak ada item yang sedang diproses untuk diselesaikan.'
                );
            }

            OrderDetail::whereIn(
                'id',
                $eligibleItems->pluck('id')
            )->update([
                'kitchen_status' => $to,
            ]);

            $allItems = OrderDetail::where(
                'order_id',
                $lockedOrder->id
            )->get();

            if ($allItems->every(
                fn ($item) => $item->kitchen_status === 'selesai'
            )) {
                $lockedOrder->status = 'siap';
            } elseif ($allItems->contains(
                fn ($item) => in_array(
                    $item->kitchen_status,
                    ['diproses', 'selesai'],
                    true
                )
            )) {
                $lockedOrder->status = 'diproses';
            } else {
                $lockedOrder->status = 'menunggu';
            }

            $lockedOrder->save();

            return [
                'order' => $lockedOrder,
                'updated_items' => $eligibleItems->count(),
            ];
        });

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $to === 'diproses'
                ? 'KITCHEN_START'
                : 'KITCHEN_COMPLETE',
            'description' => 'Dapur '
                . auth()->user()->kitchen_id
                . ' mengubah '
                . $result['updated_items']
                . ' item pesanan '
                . $result['order']->order_number
                . ' menjadi '
                . $to,
            'ip_address' => request()->ip(),
        ]);

        return response()->json([
            'message' => $to === 'diproses'
                ? 'Pesanan mulai diproses'
                : 'Item pesanan selesai diproses',
            'data' => [
                'order_id' => $result['order']->id,
                'order_number' => $result['order']->order_number,
                'order_status' => $result['order']->status,
                'updated_items' => $result['updated_items'],
            ],
        ]);
    }
}
