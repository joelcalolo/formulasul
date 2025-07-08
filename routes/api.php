<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RentalRequestController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasseioController;

// Rotas públicas de autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/passeios/{id}', [PasseioController::class, 'show'])->name('passeios.show');

// Rotas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Rotas para Clientes
    Route::post('/rental-requests', [RentalRequestController::class, 'store']);
    Route::get('/rental-requests', [RentalRequestController::class, 'index']);
    Route::get('/rentals', [RentalController::class, 'index']);
    Route::get('/cars', [CarController::class, 'index']);
    Route::get('/cars/{id}/check-availability', [CarController::class, 'checkAvailability']);
    Route::get('/transfers', [TransferController::class, 'index']);
    Route::post('/transfers', [TransferController::class, 'store']);
    
    // Rotas para Administradores
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('rental-requests', [RentalRequestController::class, 'adminIndex']);
        Route::get('pending-counts', [AdminController::class, 'getPendingCounts']);
        Route::put('rental-requests/{id}/confirm', [RentalRequestController::class, 'confirm']);
        Route::get('rentals', [RentalController::class, 'adminIndex']);
        Route::apiResource('cars', CarController::class)->except(['index', 'show']);
        Route::get('transfers', [TransferController::class, 'adminIndex']);
        Route::get('transfers/all', [TransferController::class, 'getAllTransfers']);
        Route::get('transfers/history', [TransferController::class, 'getTransferHistory']);
        Route::put('transfers/{id}/confirm', [TransferController::class, 'confirm']);
        Route::put('transfers/{id}/reject', [TransferController::class, 'reject']);
        Route::put('transfers/{id}/status', [TransferController::class, 'updateStatus']);
    });
});

