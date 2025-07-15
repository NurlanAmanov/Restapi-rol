<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/login', [AuthController::class, 'Login']);
Route::post('/register', [AuthController::class, 'Register']);

// Public - hər kəs görə bilər
Route::get('/allblogs', [BlogController::class, 'index']);

// Authenticated istifadəçilər üçün
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Admin icazəli route-lar
    Route::middleware('role:admin')->group(function () {
        Route::post('/register', [AuthController::class, 'Register']);
        Route::get('/users', [AuthController::class, 'Users']);
        Route::put('/user/{id}/role', [AuthController::class, 'changeRole']);
        Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
    });

    // Editor icazəli route-lar
    Route::middleware('role:editor')->group(function () {
        Route::post('/blog', [BlogController::class, 'store']);
        Route::put('/blogs/{id}', [BlogController::class, 'update']);
    });

    // Ehtiyac olsa:
    // Route::get('/user', fn(Request $request) => $request->user());
});
