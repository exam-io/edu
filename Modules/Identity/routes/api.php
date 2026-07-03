<?php

use Illuminate\Support\Facades\Route;
use Modules\Identity\Http\Controllers\AuthController;

Route::prefix('auth')->middleware(['tenant', 'identity.tenant'])->group(function (): void {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:identity-auth-register');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:identity-auth-login');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:identity-auth-forgot-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:identity-auth-reset-password');
});

Route::prefix('auth')->middleware(['tenant', 'identity.tenant', 'auth:sanctum', 'identity.user.active'])->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->middleware('throttle:identity-auth-verify-email');
    Route::get('/me', [AuthController::class, 'me'])->middleware('identity.email.verified');
    Route::get('/context', [AuthController::class, 'context'])->middleware('identity.email.verified');
});
