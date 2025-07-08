<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalRequestController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PasseioController;
use App\Http\Controllers\AdminController;

// Aplicando middleware web globalmente
Route::middleware('web')->group(function () {
// Rotas Públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.alt');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
Route::get('/cars/{car}/gallery', [CarController::class, 'gallery'])->name('cars.gallery');
Route::get('/aluguel', fn() => view('aluguel'))->name('aluguel');

// Autenticação
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rotas Autenticadas
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Rotas para Clientes
    Route::resource('transfers', TransferController::class)->only(['index', 'store', 'show']);
    Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create');
    Route::get('/transfers/{transfer}', [TransferController::class, 'show'])->name('transfers.show');
    Route::delete('/transfers/{transfer}', [TransferController::class, 'destroy'])->name('transfers.destroy');
    
    // Rotas para Solicitações de Aluguel
    Route::resource('rental-requests', RentalRequestController::class);
    
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    
    // Rotas para Administradores
    Route::middleware('role:admin')->group(function () {
        // Dashboard Admin
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/pending-counts', [AdminController::class, 'getPendingCounts'])->name('admin.pending-counts');
        
        // Gerenciamento de Carros
        Route::post('/admin/cars', [AdminController::class, 'storeCar'])->name('admin.cars.store');
        Route::get('/admin/cars/{id}/edit', [AdminController::class, 'editCar'])->name('admin.cars.edit');
        Route::put('/admin/cars/{id}', [AdminController::class, 'updateCar'])->name('admin.cars.update');
        Route::delete('/admin/cars/{id}', [AdminController::class, 'destroyCar'])->name('admin.cars.destroy');
        
        // Gerenciamento de Reservas
        Route::get('/admin/rental-requests/{id}', [AdminController::class, 'getRentalRequest'])->name('admin.rental-requests.show');
        Route::patch('/admin/rental-requests/{id}/confirm', [AdminController::class, 'confirmRentalRequest'])->name('admin.rental-requests.confirm');
        Route::patch('/admin/rental-requests/{id}/reject', [RentalRequestController::class, 'reject'])->name('admin.rental-requests.reject');
        
        // Gerenciamento de Transfers
        Route::get('/admin/transfers/{id}', [AdminController::class, 'getTransfer'])->name('admin.transfers.show');
        Route::patch('/admin/transfers/{id}/confirm', [AdminController::class, 'confirmTransfer'])->name('admin.transfers.confirm');
        Route::patch('/admin/transfers/{id}/reject', [AdminController::class, 'rejectTransfer'])->name('admin.transfers.reject');
        
        // Rotas existentes
        Route::get('/admin/rental-requests', [RentalRequestController::class, 'adminIndex'])->name('admin.rental-requests.index');
        Route::put('/rental-requests/{rental_request}/confirm', [RentalRequestController::class, 'confirm'])->name('rental-requests.confirm');
        Route::put('/rental-requests/{rental_request}/finish', [RentalRequestController::class, 'finish'])->name('rental-requests.finish');
        Route::get('/admin/rentals', [RentalController::class, 'adminIndex'])->name('admin.rentals.index');
        Route::get('/admin/transfers', [TransferController::class, 'adminIndex'])->name('admin.transfers.index');
        Route::put('/transfers/{transfer}/confirm', [TransferController::class, 'confirm'])->name('transfers.confirm');
        Route::put('/transfers/{transfer}/reject', [TransferController::class, 'reject'])->name('transfers.reject');

        // Rotas para gerenciamento de carros
        Route::get('/admin/cars', [CarController::class, 'adminIndex'])->name('admin.cars.index');
        Route::get('/admin/cars/create', [CarController::class, 'create'])->name('admin.cars.create');
        Route::post('/admin/cars', [CarController::class, 'store'])->name('admin.cars.store');
        Route::put('/admin/cars/{car}', [CarController::class, 'update'])->name('admin.cars.update');
        Route::delete('/admin/cars/{car}', [CarController::class, 'destroy'])->name('admin.cars.destroy');
    });
});

Route::get('/suporte', [SupportController::class, 'index'])->name('suporte');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
// Rota removida - duplicada com passeios.index

// Rota para demonstração do sistema de notificações
Route::get('/notification-demo', fn() => view('notification-demo'))->name('notification.demo');

    // Rotas de recuperação de senha
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])
        ->middleware('guest')
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->middleware('guest')
        ->name('password.email');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->middleware('guest')
        ->name('password.update');
});

// Rotas para Passeios
Route::get('/passeios', [PasseioController::class, 'index'])->name('passeios.index');
Route::get('/passeios/{id}', [PasseioController::class, 'show'])->name('passeios.show');
Route::post('/passeios/{id}/reservar', [PasseioController::class, 'reservar'])->name('passeios.reservar');