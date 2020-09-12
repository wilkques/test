<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $token = auth('jwt')->attempt($request->only('account', 'password'));

        if (!$token) {
            return response()->json([
                'message' => 'unauthorized, please check account or password'
            ], 403);
        }

        return response()->json([
            'token' => "Bearer $token"
        ], 200);
    }

    public function logout()
    {
        auth('jwt')->logout();

        return response()->json('success', 200);
    }
}
