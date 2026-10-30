<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function main()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return redirect('/login');
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:5',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Validasi gagal.',
                        'errors' => $validator->errors(),
                    ], 422);
                }

                return redirect('/login')
                    ->withErrors($validator)
                    ->withInput();
            }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Login berhasil.',
                        'redirect' => '/dashboard',
                    ]);
                }

                return redirect()->intended('/dashboard');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Email atau password salah.',
                ], 401);
            }

            return redirect('/login')->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
                ], 500);
            }

            return redirect('/login')->withErrors([
                'error' => 'An error occurred during login. Please try again later.',
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
