<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Kitchen;
use App\Models\Ingredient;
use App\Models\Stock;

class DashboardController extends Controller
{
    public function index()
    {
        $lowStocks = Stock::with('ingredient')
            ->whereColumn('quantity', '<=', 'minimum_stock')
            ->get();

        return response()->json([
            'message' => 'Dashboard admin berhasil diambil',
            'data' => [
                'total_users' => User::count(),
                'total_menus' => Menu::count(),
                'total_categories' => Category::count(),
                'total_kitchens' => Kitchen::count(),
                'total_ingredients' => Ingredient::count(),
                'low_stocks' => $lowStocks,
            ]
        ]);
    }
}