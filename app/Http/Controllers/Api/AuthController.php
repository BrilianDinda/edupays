<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where(
            'username',
            $request->username
        )->first();

        // USERNAME TIDAK ADA
        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Username tidak ditemukan'
            ], 401);
        }

        // PASSWORD SALAH
        if (!Hash::check(
            $request->password,
            $user->password
        )) {

            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        // LOGIN BERHASIL
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}