<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('ingredient')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data stok berhasil diambil',
            'data' => $stocks
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id|unique:stocks,ingredient_id',
            'quantity' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
        ]);

        $stock = Stock::create($validated);

        return response()->json([
            'message' => 'Stok berhasil ditambahkan',
            'data' => $stock->load('ingredient')
        ], 201);
    }

    public function show(Stock $stock)
    {
        return response()->json([
            'message' => 'Data stok berhasil diambil',
            'data' => $stock->load('ingredient')
        ]);
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'ingredient_id' => 'sometimes|required|exists:ingredients,id|unique:stocks,ingredient_id,' . $stock->id,
            'quantity' => 'sometimes|required|numeric|min:0',
            'minimum_stock' => 'sometimes|required|numeric|min:0',
        ]);

        $stock->update($validated);

        return response()->json([
            'message' => 'Stok berhasil diperbarui',
            'data' => $stock->fresh()->load('ingredient')
        ]);
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();

        return response()->json([
            'message' => 'Stok berhasil dihapus'
        ]);
    }
}