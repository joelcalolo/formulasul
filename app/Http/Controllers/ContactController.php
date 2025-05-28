<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // TODO: Implement email sending or storage logic
            Log::info('Contact form submitted', $validated);
            return redirect()->route('suporte')->with('success', 'Mensagem enviada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error processing contact form', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erro ao enviar mensagem. Tente novamente.');
        }
    }
}