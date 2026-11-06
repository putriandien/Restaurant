<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'kitchen'])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Data user berhasil diambil',
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'kitchen_id' => 'nullable|exists:kitchens,id',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        $user = User::create($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_USER',
            'description' => 'Menambahkan user "' . $user->name . '"',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'User berhasil ditambahkan',
            'data' => $user->load(['role', 'kitchen'])
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'message' => 'Data user berhasil diambil',
            'data' => $user->load(['role', 'kitchen'])
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'sometimes|required|exists:roles,id',
            'kitchen_id' => 'nullable|exists:kitchens,id',
            'name' => 'sometimes|required|string|max:255',
            'username' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_USER',
            'description' => 'Mengubah user "' . $user->name . '"',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'User berhasil diperbarui',
            'data' => $user->fresh()->load(['role', 'kitchen'])
        ]);
    }

    public function destroy(User $user)
    {
        $userName = $user->name;

        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE_USER',
            'description' => 'Menghapus user "' . $userName . '"',
            'ip_address' => $request->ip(),
        ]);
        
        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }
}