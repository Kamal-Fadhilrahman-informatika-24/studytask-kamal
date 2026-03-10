<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6'
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);

    return response()->json([
        'message' => 'User berhasil dibuat',
        'user' => $user
    ]);
}

    public function login(Request $request)
{
    $credentials = $request->only('email','password');

    if (!$token = JWTAuth::attempt($credentials)) {
        return response()->json([
            'message' => 'Email atau password salah'
        ],401);
    }

    return response()->json([
        'token' => $token,
        'type' => 'bearer'
    ]);
}

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'token' => JWTAuth::refresh(JWTAuth::getToken())
        ]);
    }

}