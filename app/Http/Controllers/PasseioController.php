<?php

namespace App\Http\Controllers;

use App\Models\Passeio;
use Illuminate\Http\Request;

class PasseioController extends Controller
{
    /**
     * Exibe a lista de passeios disponíveis
     */
    public function index()
    {
        $passeios = Passeio::all();
        return view('passeios', compact('passeios'));
    }

    /**
     * Exibe os detalhes de um passeio específico
     */
    public function show($id)
    {
        $passeio = Passeio::findOrFail($id);
        return view('passeio-detalhes', compact('passeio'));
    }

    /**
     * Processa a reserva de um passeio
     */
    public function reservar(Request $request, $id)
    {
        $request->validate([
            'data' => 'required|date|after:today',
            'pessoas' => 'required|integer|min:1',
            'observacoes' => 'nullable|string|max:500'
        ]);

        $passeio = Passeio::findOrFail($id);
        
        // Aqui você pode adicionar a lógica para criar a reserva
        // Por exemplo:
        // $reserva = ReservaPasseio::create([
        //     'passeio_id' => $passeio->id,
        //     'user_id' => auth()->id(),
        //     'data' => $request->data,
        //     'pessoas' => $request->pessoas,
        //     'observacoes' => $request->observacoes,
        //     'status' => 'pendente'
        // ]);

        return redirect()->back()->with('success', 'Reserva solicitada com sucesso! Entraremos em contato para confirmar.');
    }
} 