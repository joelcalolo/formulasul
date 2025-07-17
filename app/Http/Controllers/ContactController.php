<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Exibe a página de contato
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Processa o formulário de contato
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'tipo_contato' => 'nullable|string|in:geral,passeio',
            'destino_passeio' => 'nullable|string|max:255',
            'data_passeio' => 'nullable|date|after_or_equal:today',
            'pessoas' => 'nullable|integer|min:1|max:20',
        ]);

        try {
            // Determinar o tipo de contato
            $tipoContato = $validated['tipo_contato'] ?? 'geral';
            
            // Preparar dados para o email
            $emailData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? 'Não informado',
                'message' => $validated['message'] ?? 'Nenhuma mensagem adicional',
                'tipo_contato' => $tipoContato,
            ];

            // Adicionar dados específicos para passeios
            if ($tipoContato === 'passeio') {
                $emailData['destino_passeio'] = $validated['destino_passeio'] ?? 'Não informado';
                $emailData['data_passeio'] = $validated['data_passeio'] ?? 'Não informado';
                $emailData['pessoas'] = $validated['pessoas'] ?? 'Não informado';
                $emailData['subject'] = 'Nova Solicitação de Passeio - ' . $validated['name'];
            } else {
                $emailData['subject'] = 'Nova Mensagem de Contato - ' . $validated['name'];
            }

            // Enviar email
            Mail::to('formulasul.cars@gmail.com')->send(new ContactFormMail($emailData));

            Log::info('Contact form submitted successfully', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'tipo_contato' => $tipoContato
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.'
                ]);
            }

            return redirect()->route('contact')->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
        } catch (\Exception $e) {
            Log::error('Error processing contact form', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao enviar mensagem. Tente novamente.'
                ], 500);
            }

            return back()->with('error', 'Erro ao enviar mensagem. Tente novamente.');
        }
    }
}