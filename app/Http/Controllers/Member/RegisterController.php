<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\RegisterStoreRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(RegisterStoreRequest $request)
    {
        User::create([
            'account' => $request->account,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
