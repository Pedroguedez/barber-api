<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthResquest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;

class AuthController extends Controller
{
    public function login(AuthResquest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $user = $request->user();

        $token = $user->createToken('auth', ['*'])->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(AuthResquest $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}
