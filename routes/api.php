<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TicketCategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ForgotPasswordController;

// Rute autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Rute khusus Admin dengan prefix "admin"
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Welcome Admin']);
        });

        // Manajemen Event dan TicketCategory untuk admin
        Route::prefix('admin')->group(function () {
            Route::apiResource('/events', EventController::class);
            Route::apiResource('/ticket-categories', TicketCategoryController::class);
        });
    });

    // Rute khusus User untuk operasi baca (read-only)
    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', function () {
            return response()->json(['message' => 'Welcome User']);
        });
        // Endpoint untuk mendapatkan daftar dan detail event
        Route::get('/events', [EventController::class, 'index']);
        Route::get('/events/{id}', [EventController::class, 'show']);
        // Endpoint untuk mendapatkan daftar dan detail ticket categories
        Route::get('/ticket-categories', [TicketCategoryController::class, 'index']);
        Route::get('/ticket-categories/{id}', [TicketCategoryController::class, 'show']);
        // Endpoint untuk membuat order
        Route::post('/orders', [OrderController::class, 'store']);
        // Endpoint untuk mendapatkan riwayat order
        Route::get('/orders/history', [OrderController::class, 'history']);
    });
});
