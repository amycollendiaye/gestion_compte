<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'success' => false,
            'message' => 'Identifiants invalides'
        ], 401);
    }

    $user = Auth::user(); 
    var_dump($user);
    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'user' => $user,
        'token' => $token
    ]);
}

}
