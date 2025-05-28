<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RentalRequestController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\TransferController;

// Rotas públicas de autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Rotas para Clientes
    Route::post('/rental-requests', [RentalRequestController::class, 'store']);
    Route::get('/rental-requests', [RentalRequestController::class, 'index']);
    Route::get('/rentals', [RentalController::class, 'index']);
    Route::get('/cars', [CarController::class, 'index']);
    Route::get('/transfers', [TransferController::class, 'index']);
    Route::post('/transfers', [TransferController::class, 'store']);
    
    // Rotas para Administradores
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('rental-requests', [RentalRequestController::class, 'adminIndex']);
        Route::put('rental-requests/{id}/confirm', [RentalRequestController::class, 'confirm']);
        Route::get('rentals', [RentalController::class, 'adminIndex']);
        Route::apiResource('cars', CarController::class)->except(['index', 'show']);
        Route::get('transfers', [TransferController::class, 'adminIndex']);
        Route::put('transfers/{id}/confirm', [TransferController::class, 'confirm']);
    });
});