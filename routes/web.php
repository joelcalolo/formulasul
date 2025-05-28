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

// Rotas Públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

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
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    
    
    // Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Rotas para Clientes
    Route::resource('transfers', TransferController::class)->only(['index', 'store', 'show']);
    Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create');
    Route::get('/transfers/{transfer}', [TransferController::class, 'show'])->name('transfers.show');
    Route::delete('/transfers/{transfer}', [TransferController::class, 'destroy'])->name('transfers.destroy');
    
    
    Route::get('/rental-requests', [RentalRequestController::class, 'index'])->name('rental-requests.index');
    Route::get('/rental-requests/create', [RentalRequestController::class, 'create'])->name('rental-requests.create');
    Route::post('/rental-requests', [RentalRequestController::class, 'store'])->name('rental-requests.store');
    
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
    Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');
    
    // Rotas para Administradores
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/rental-requests', [RentalRequestController::class, 'adminIndex'])->name('admin.rental-requests.index');
        Route::put('/rental-requests/{rental_request}/confirm', [RentalRequestController::class, 'confirm'])->name('rental-requests.confirm');
        Route::get('/admin/rentals', [RentalController::class, 'adminIndex'])->name('admin.rentals.index');
        Route::get('/admin/transfers', [TransferController::class, 'adminIndex'])->name('admin.transfers.index');
        Route::put('/transfers/{transfer}/confirm', [TransferController::class, 'confirm'])->name('transfers.confirm');
    });
});

// Recurso para RentalRequestController
Route::resource('rental-requests', RentalRequestController::class);

// Adicionando a rota para exibir um pedido de aluguel específico
Route::get('/rental-requests/{id}', [RentalRequestController::class, 'show'])->name('rental-requests.show');

Route::get('/suporte', [SupportController::class, 'index'])->name('suporte');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/passeios', fn() => view('passeios'))->name('passeios');