<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::latest()->get();

        return response()->json([
            'message' => 'Data bahan berhasil diambil',
            'data' => $ingredients
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name',
            'unit' => 'required|string|max:50',
        ]);

        $ingredient = Ingredient::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_INGREDIENT',
            'description' => 'Menambahkan bahan "' . $ingredient->name . '"',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Bahan berhasil ditambahkan',
            'data' => $ingredient
        ], 201);
    }

    public function show(Ingredient $ingredient)
    {
        return response()->json([
            'message' => 'Data bahan berhasil diambil',
            'data' => $ingredient
        ]);
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('ingredients', 'name')->ignore($ingredient->id),
            ],
            'unit' => 'sometimes|required|string|max:50',
        ]);

        $ingredient->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_INGREDIENT',
            'description' => 'Mengubah bahan "' . $ingredient->name . '"',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Bahan berhasil diperbarui',
            'data' => $ingredient->fresh()
        ]);
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredientName = $ingredient->name;

        $ingredient->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_INGREDIENT',
            'description' => 'Menghapus bahan "' . $ingredientName . '"',
            'ip_address' => $request->ip(),
        ]);
        
        return response()->json([
            'message' => 'Bahan berhasil dihapus'
        ]);
    }
}