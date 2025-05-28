<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transfers = $user->role === 'admin' ? Transfer::with('user')->get() : $user->transfers()->with('user')->get();
        return view('transfers.index', compact('transfers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'origem' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'data_hora' => 'required|date|after_or_equal:now',
            'tipo' => 'required|in:aeroporto,cidade,intermunicipal',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pendente';
        $validated['email_enviado'] = false;

        Transfer::create($validated);

        return redirect()->route('transfers.index')->with('success', 'Solicitação de transfer criada com sucesso!');
    }

    public function show(Transfer $transfer)
    {
        $this->authorize('view', $transfer);
        return view('transfers.show', compact('transfer'));
    }

    public function confirm(Transfer $transfer)
    {
        $this->authorize('admin', Transfer::class);
        $transfer->update(['status' => 'confirmado']);
        return redirect()->route('transfers.index')->with('success', 'Transfer confirmado!');
    }

    public function adminIndex()
    {
        $this->authorize('admin', Transfer::class);
        $transfers = Transfer::with('user')->get();
        return view('admin.transfers.index', compact('transfers'));
    }
}