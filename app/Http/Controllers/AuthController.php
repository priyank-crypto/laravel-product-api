<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' =>'required|string|max:25',
            'email' =>'required|email|unique:users',
            'password' => 'required|min:6'
        ]);
        $user = User::create($validated);
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([ 'token' => $token, 'user' => $user ], 201);
    }

    public function login(Request $request) { 
        $validated = $request->validate([
             'email' => 'required|email',
             'password' => 'required' 
        ]); 
        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Hash::check($validated['password'], $user->password)) { 
            return response()->json([
                'message' => 'Invalid credentials' ], 401);
            }
        $token = $user->createToken('api-token')->plainTextToken; 
        return response()->json([ 'token' => $token, 'user' => $user ]);
    }
    public function logout(Request $request) { 
        $request->user()->currentAccessToken()->delete(); 
        return response()->json([ 'message' => 'Logged out successfully' ]); 
    }
}
