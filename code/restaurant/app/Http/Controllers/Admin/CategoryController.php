<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return response()->json([
            'message' => 'Data kategori berhasil diambil',
            'data' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_CATEGORY',
            'description' => 'Menambahkan kategori "' . $category->name . '"',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ], 201);
    }

    public function show(Category $category)
    {
        return response()->json([
            'message' => 'Data kategori berhasil diambil',
            'data' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_CATEGORY',
            'description' => 'Mengubah kategori "' . $category->name . '"',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category->fresh()
        ]);
    }

    public function destroy(Category $category)
    {
        $categoryName = $category->name;

        $category->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_CATEGORY',
            'description' => 'Menghapus kategori "' . $categoryName . '"',
            'ip_address' => $request->ip(),
        ]);
        
        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}