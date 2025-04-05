<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Gunakan Sanctum untuk membuat token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        // Generate OTP (misal 6 digit) dan simpan ke database
        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);
    
        // Kirim OTP via email menggunakan notifikasi atau Mail facade
        \Mail::raw("Kode OTP Anda adalah: {$otp}. Kode ini berlaku selama 5 menit.", function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Verifikasi OTP untuk Login');
        });
    
        return response()->json([
            'message' => 'OTP telah dikirim ke email Anda. Silahkan masukkan OTP untuk verifikasi.'
        ]);
    }
    
    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Cek OTP dan waktu kadaluarsa
        if ($user->otp !== $request->otp || now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['message' => 'OTP salah atau sudah kadaluarsa'], 401);
        }

        // Hapus OTP dan waktu kadaluarsa setelah verifikasi
        $user->update([
            'otp' => null,
            'otp_expires_at' => null
        ]);

        // Buat token (misalnya menggunakan Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => $user
        ]);
    }


    // Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    // Get User Profile
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
}
