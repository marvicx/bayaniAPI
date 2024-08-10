<?php

use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\auth\ForgotPasswordController;
use App\Http\Controllers\api\auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/password/forgot', [ForgotPasswordController::class, 'forgotPassword']);
    Route::post('/password/reset', [ResetPasswordController::class, 'resetPassword']);

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
        Route::post('/me', [AuthController::class, 'me'])->name('me');
    });
});
