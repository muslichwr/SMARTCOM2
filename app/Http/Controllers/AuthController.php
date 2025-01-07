<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['name'] = $auth->name;

            if ($auth->hasRole('admin')) {
                $success['role'] = 'admin';
                $message = 'Login Berhasil Sebagai Admin';
            } elseif ($auth->hasRole('user')) {
                $success['role'] = 'user';
                $message = 'Login Berhasil Sebagai Student';
            } else {
                $success['role'] = 'Anda Siapa?';
                $message = 'Anda Tidak Memiliki Peran Yang Valid';
            }

            return response()->json(['success' => true, 'message' => $message, 'data' => $success]);
        } else {
            return response()->json(['success' => false, 'message' => 'Periksa Email & Kata Sandi']);
        }
    }
}
