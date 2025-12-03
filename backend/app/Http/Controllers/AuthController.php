<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Map 'username' to 'email' for authentication
        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'refresh' => 'refresh_token_placeholder', // Sanctum uses long-lived tokens
                'access' => $token,
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function refresh(Request $request)
    {
        // Sanctum doesn't use refresh tokens by default. 
        // You would typically just issue a new token if the old one is expiring, 
        // but that requires the user to be authenticated.
        return response()->json([
            'access' => 'new_mock_token_placeholder',
        ]);
    }
}
