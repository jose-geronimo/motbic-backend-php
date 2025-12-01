<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Mock authentication
        if ($credentials['username'] === 'admin' && $credentials['password'] === 'admin123') {
             return response()->json([
                'refresh' => 'mock_refresh_token_' . time(),
                'access' => 'mock_access_token_' . time(),
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function refresh(Request $request)
    {
        return response()->json([
            'access' => 'mock_new_access_token_' . time(),
        ]);
    }
}
