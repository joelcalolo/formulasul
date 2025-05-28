<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\RentalRequest;
use App\Models\Transfer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $cars = Car::all(); // Ou use ->take(5) para limitar o número de carros exibidos
        return view('home', compact('cars'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        $rentalRequests = $user->role === 'admin' ? RentalRequest::all() : $user->rentalRequests;
        $transfers = $user->role === 'admin' ? Transfer::all() : $user->transfers;
        $cars = Car::all();

        return view('dashboard', compact('rentalRequests', 'transfers', 'cars'));
    }
}