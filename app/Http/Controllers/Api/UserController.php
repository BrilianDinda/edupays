<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ================= ADD PARENT =================
    public function addParent(Request $request)
    {
        // cek username sudah dipakai atau belum
        $cek = User::where(
            'username',
            $request->username
        )->first();

        if ($cek) {

            return response()->json([
                'success' => false,
                'message' => 'Username sudah digunakan'
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make(
                $request->password
            ),
            'role' => 'orang_tua',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Akun orang tua berhasil dibuat',
            'data' => $user
        ]);
    }

    // ================= UPDATE PARENT =================
    public function updateParent(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $data = [
            'name' => $request->name ?? $user->name,
            'username' => $request->username ?? $user->username,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Akun orang tua berhasil diupdate',
            'data' => $user
        ]);
    }

    // ================= DELETE PARENT =================
    public function destroyParent($id)
    {
        $user = User::where('role', 'orang_tua')->find($id);

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Orang tua tidak ditemukan'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Orang tua berhasil dihapus'
        ]);
    }

    // ================= GET PARENTS =================
    public function parents()
    {
        $parents = User::where(
            'role',
            'orang_tua'
        )->get();

        return response()->json([
            'success' => true,
            'data' => $parents
        ]);
    }
}