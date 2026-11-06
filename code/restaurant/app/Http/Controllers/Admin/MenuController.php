<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['category', 'kitchen'])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data menu berhasil diambil',
            'data' => $menus
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'kitchen_id' => 'required|exists:kitchens,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:255',
            'status' => 'required|in:tersedia,habis,nonaktif',
        ]);

        $menu = Menu::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_MENU',
            'description' => 'Menambahkan menu "' . $menu->name . '"',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Menu berhasil ditambahkan',
            'data' => $menu->load(['category', 'kitchen'])
        ], 201);
    }

    public function show(Menu $menu)
    {
        return response()->json([
            'message' => 'Data menu berhasil diambil',
            'data' => $menu->load(['category', 'kitchen'])
        ]);
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'kitchen_id' => 'sometimes|required|exists:kitchens,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'nullable|string|max:255',
            'status' => [
                'sometimes',
                'required',
                Rule::in(['tersedia', 'habis', 'nonaktif']),
            ],
        ]);

        $menu->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_MENU',
            'description' => 'Mengubah menu "' . $menu->name . '"',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
         
        'message' => 'Menu berhasil diperbarui',
            'data' => $menu->fresh()->load(['category', 'kitchen'])
        ]);
    }

    public function destroy(Menu $menu)
    {
        $menuName = $menu->name;

        $menu->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_MENU',
            'description' => 'Menghapus menu "' . $menuName . '"',
            'ip_address' => $request->ip(),
        ]);
        
        return response()->json([
            'message' => 'Menu berhasil dihapus'
        ]);
    }
}