<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Username atau password salah'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Akun tidak aktif'
            ], 403);
        }

        $token = JWTAuth::fromUser($user);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'LOGIN',
            'description' => $user->name . ' berhasil login',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'kitchen_id' => $user->kitchen_id,
            ]
        ]);
    }

    public function me()
    {
        $user = JWTAuth::user();

        return response()->json([
            'user' => $user
        ]);
    }

    public function logout()
    {
        $user = JWTAuth::user();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'LOGOUT',
            'description' => $user->name . ' berhasil logout',
            'ip_address' => $request->ip(),
        ]);

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}