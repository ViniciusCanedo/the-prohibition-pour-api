<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('/send-reset-password-email', [AuthController::class, 'sendPasswordResetEmail'])
        ->name('sendPasswordResetEmail');

    Route::post('/reset-password/{token}', [AuthController::class, 'resetPassword'])
        ->name('resetPassword');

    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::prefix('users')->group(function (): void {
    Route::get('/', [UserController::class, 'index'])
        ->name('getUsers');

    Route::post('/', [UserController::class, 'store'])
        ->name('postUser');

    Route::get('/{id}', [UserController::class, 'show'])
        ->name('getUser');

    Route::put('/{id}', [UserController::class, 'update'])
        ->name('putUser');

    Route::delete('/{id}', [UserController::class, 'destroy'])
        ->name('deleteUser');
});
