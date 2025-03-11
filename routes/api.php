<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketCategoryController;

// Rute autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Rute khusus Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Welcome Admin']);
        });

        // Manajemen Event
        Route::apiResource('/events', EventController::class);

        // Manajemen Tiket (Kategori Tiket)
        Route::apiResource('/tickets', TicketCategoryController::class);
    });

    // Rute khusus User
    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', function () {
            return response()->json(['message' => 'Welcome User']);
        });
    });
});
