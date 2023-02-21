<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', ChatController::class)
    ->middleware('auth')
    ->name('index');

Route::get('/auth', [AuthController::class, 'create'])
    ->middleware('guest')
    ->name('auth.create');

Route::post('/auth', [AuthController::class, 'store'])
    ->middleware('guest')
    ->name('auth.store');

Route::delete('/auth', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('auth.destroy');
