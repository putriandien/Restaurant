<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with('ingredient')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data pergerakan stok berhasil diambil',
            'data' => $movements
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'type' => 'required|in:in,out,adjust',
            'quantity' => 'required|numeric|min:0',
            'reference' => 'nullable|string|max:255',
        ]);

        $movement = DB::transaction(function () use ($validated) {

            $stock = Stock::where(
                'ingredient_id',
                $validated['ingredient_id']
            )->lockForUpdate()->first();

            if (!$stock) {
                abort(404, 'Data stok untuk bahan ini belum tersedia');
            }

            if ($validated['type'] === 'in') {
                $stock->quantity += $validated['quantity'];
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'STOCK_IN',
                'description' => 'Menambahkan stok "' . $movement->ingredient->name . '" sebanyak ' . $movement->quantity . ' ' . $movement->ingredient->unit,
                'ip_address' => $request->ip(),
            ]);

            if ($validated['type'] === 'out') {
                if ($stock->quantity < $validated['quantity']) {
                    abort(422, 'Stok tidak mencukupi');
                }

                $stock->quantity -= $validated['quantity'];
            }
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'STOCK_OUT',
                'description' => 'Mengurangi stok "' . $movement->ingredient->name . '" sebanyak ' . $movement->quantity . ' ' . $movement->ingredient->unit,
                'ip_address' => $request->ip(),
            ]);

            if ($validated['type'] === 'adjust') {
                $stock->quantity = $validated['quantity'];
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'STOCK_ADJUST',
                'description' => 'Menyesuaikan stok "' . $movement->ingredient->name . '" menjadi ' . $movement->quantity . ' ' . $movement->ingredient->unit,
                'ip_address' => $request->ip(),
            ]);

            $stock->save();
            
            return StockMovement::create([
                'ingredient_id' => $validated['ingredient_id'],
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'reference' => $validated['reference'] ?? null,
            ]);
        });

        return response()->json([
            'message' => 'Pergerakan stok berhasil ditambahkan',
            'data' => $movement->load('ingredient')
        ], 201);
    }

    public function show(StockMovement $stockMovement)
    {
        return response()->json([
            'message' => 'Data pergerakan stok berhasil diambil',
            'data' => $stockMovement->load('ingredient')
        ]);
    }

    public function update(Request $request, StockMovement $stockMovement)
    {
        return response()->json([
            'message' => 'Riwayat pergerakan stok tidak dapat diubah'
        ], 405);
    }

    public function destroy(StockMovement $stockMovement)
    {
        return response()->json([
            'message' => 'Riwayat pergerakan stok tidak dapat dihapus'
        ], 405);
    }
}