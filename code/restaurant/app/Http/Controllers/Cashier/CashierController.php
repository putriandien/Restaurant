<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'message' => 'Dashboard kasir berhasil diambil',
            'data' => [
                'total_orders_today' => Order::whereDate(
                    'order_date',
                    today()
                )->count(),

                'total_transactions_today' => Payment::whereDate(
                    'payment_time',
                    today()
                )->where('status', 'berhasil')->count(),

                'total_sales_today' => Payment::whereDate(
                    'payment_time',
                    today()
                )->where('status', 'berhasil')->sum('amount'),
            ]
        ]);
    }

    public function menus()
    {
        $menus = Menu::with(['category', 'kitchen'])
            ->where('status', 'tersedia')
            ->orderBy('name')
            ->get();

        return response()->json([
            'message' => 'Daftar menu berhasil diambil',
            'data' => $menus
        ]);
    }
}
