<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kitchen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KitchenController extends Controller
{
    public function index()
    {
        $kitchens = Kitchen::latest()->get();

        return response()->json([
            'message' => 'Data dapur berhasil diambil',
            'data' => $kitchens
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:kitchens,name',
            'description' => 'nullable|string',
        ]);

        $kitchen = Kitchen::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_KITCHEN',
            'description' => 'Menambahkan dapur "' . $kitchen->name . '"',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Dapur berhasil ditambahkan',
            'data' => $kitchen
        ], 201);
    }

    public function show(Kitchen $kitchen)
    {
        return response()->json([
            'message' => 'Data dapur berhasil diambil',
            'data' => $kitchen
        ]);
    }

    public function update(Request $request, Kitchen $kitchen)
    {
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('kitchens', 'name')->ignore($kitchen->id),
            ],
            'description' => 'nullable|string',
        ]);

        $kitchen->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_KITCHEN',
            'description' => 'Mengubah dapur "' . $kitchen->name . '"',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Dapur berhasil diperbarui',
            'data' => $kitchen->fresh()
        ]);
    }

    public function destroy(Kitchen $kitchen)
    {
        $kitchenName = $kitchen->name;

        $kitchen->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_KITCHEN',
            'description' => 'Menghapus dapur "' . $kitchenName . '"',
            'ip_address' => $request->ip(),
        ]);
        
        return response()->json([
            'message' => 'Dapur berhasil dihapus'
        ]);
    }
}