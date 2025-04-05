<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // Kirim link reset password ke email
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email tidak ditemukan'], 404);
        }

        $token = Str::random(60);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => Carbon::now()]
        );

        // Kirim email link reset
        \Mail::raw("Gunakan token berikut untuk reset password Anda: $token", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Reset Password - EventTix');
        });

        return response()->json(['message' => 'Link reset password telah dikirim ke email Anda']);
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (!$record) {
            return response()->json(['message' => 'Token tidak ditemukan'], 404);
        }

        if (!Hash::check($request->token, $record->token)) {
            return response()->json(['message' => 'Token tidak valid'], 400);
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['message' => 'Token kadaluarsa'], 400);
        }

        // Update password user
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        // Hapus token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil direset']);
    }
}
