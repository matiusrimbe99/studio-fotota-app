<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|numeric',
            'nomor_hp' => 'required|string',
            'address' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|confirmed|min:6',
        ]);

        $nomor_hp = $request['nomor_hp'];
        if ($request['nomor_hp'][0] == "0") {
            $nomor_hp = substr($nomor_hp, 1);
        }
        if ($nomor_hp[0] == "8") {
            $nomor_hp = "62" . $nomor_hp;
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
        ]);

        $user->customer()->create([
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'nomor_hp' => $nomor_hp,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil registrasi!',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak tersedia',
            ], 400);
        }

        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->username)->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Berhasil masuk!',
                'data' => [
                    'id' => $user->id,
                    'token' => $token,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'role_name' => $user->role->role_name,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah!',
        ], 403);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout',
        ]);
    }
}
