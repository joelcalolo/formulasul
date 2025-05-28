<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RentalController extends Controller
{
    // Listar aluguéis do usuário
    public function index(Request $request)
    {
        //listar aluguéis do usuário
        try {
            $rentals = $request->user()->rentals()
                ->latest()
                ->paginate(10);

            if ($request->wantsJson()) {
                return response()->json($rentals);
            }

            return view('rentals.index', compact('rentals'));
        } catch (\Exception $e) {
            Log::error('Erro ao listar aluguéis', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            if ($request->wantsJson()) {
                return response()->json(['error' => 'Erro ao listar aluguéis'], 500);
            }

            return back()->with('error', 'Erro ao carregar seus aluguéis');
        }

    }

    // Listar todos aluguéis (admin)
    public function adminIndex(Request $request)
    {
        // listar todos os aluguéis
        try {
            $rentals = Rental::latest()
                ->paginate(10);

            if ($request->wantsJson()) {
                return response()->json($rentals);
            }

            return view('admin.rentals.index', compact('rentals'));
        } catch (\Exception $e) {
            Log::error('Erro ao listar aluguéis', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            if ($request->wantsJson()) {
                return response()->json(['error' => 'Erro ao listar aluguéis'], 500);
            }

            return back()->with('error', 'Erro ao carregar os aluguéis');
        }

    }
}