<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'user:id,name,username',
            'orderDetails.menu',
            'orderDetails.kitchen',
            'payment',
        ])->orderByDesc('created_at')->get();

        return response()->json([
            'message' => 'Riwayat pesanan berhasil diambil',
            'data' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:500',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $total = 0;
            $details = [];

            foreach ($validated['items'] as $item) {
                $menu = Menu::whereKey($item['menu_id'])
                    ->where('status', 'tersedia')
                    ->first();

                if (!$menu) {
                    abort(
                        422,
                        'Menu tidak tersedia: ID ' . $item['menu_id']
                    );
                }

                $subtotal = (float) $menu->price
                    * (int) $item['quantity'];

                $total += $subtotal;

                $details[] = [
                    'menu_id' => $menu->id,
                    'kitchen_id' => $menu->kitchen_id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'subtotal' => $subtotal,
                    'notes' => $item['notes'] ?? null,
                    'kitchen_status' => 'menunggu',
                ];
            }

            $order = Order::create([
                'order_number' => 'ORD-'
                    . now()->format('YmdHis')
                    . '-' . strtoupper(bin2hex(random_bytes(3))),
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'status' => 'menunggu',
                'order_date' => now(),
            ]);

            $order->orderDetails()->createMany($details);

            return $order;
        });

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_ORDER',
            'description' => 'Membuat pesanan '
                . $order->order_number,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil dibuat',
            'data' => $order->load([
                'orderDetails.menu',
                'orderDetails.kitchen',
            ]),
        ], 201);
    }

    public function show(Order $order)
    {
        return response()->json([
            'message' => 'Detail pesanan berhasil diambil',
            'data' => $order->load([
                'user:id,name,username',
                'orderDetails.menu',
                'orderDetails.kitchen',
                'payment',
            ]),
        ]);
    }

    public function receipt(Order $order)
    {
        return response()->json([
            'message' => 'Data struk berhasil diambil',
            'data' => $order->load([
                'user:id,name,username',
                'orderDetails.menu',
                'payment',
            ]),
        ]);
    }
}
