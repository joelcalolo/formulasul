<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordResetMail;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email'
            ], [
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'Digite um e-mail válido.',
                'email.exists' => 'Este e-mail não está cadastrado em nosso sistema.'
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            Log::info('Solicitação de recuperação de senha', [
                'email' => $request->email,
                'status' => $status
            ]);

            return $status === Password::RESET_LINK_SENT
                ? back()->with('success', 'Link de recuperação enviado para seu e-mail!')
                : back()->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            Log::error('Erro ao enviar link de recuperação de senha', [
                'error' => $e->getMessage(),
                'email' => $request->email ?? null
            ]);

            return back()->withErrors([
                'error' => 'Erro ao processar sua solicitação. Por favor, tente novamente.'
            ]);
        }
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required'
            ], [
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'Digite um e-mail válido.',
                'email.exists' => 'Este e-mail não está cadastrado em nosso sistema.',
                'password.required' => 'O campo senha é obrigatório.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'password.confirmed' => 'As senhas não conferem.',
                'password_confirmation.required' => 'A confirmação de senha é obrigatória.'
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60)
                    ])->save();
                }
            );

            Log::info('Senha redefinida', [
                'email' => $request->email,
                'status' => $status
            ]);

            return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('success', 'Senha alterada com sucesso!')
                : back()->withErrors(['email' => __($status)]);

        } catch (\Exception $e) {
            Log::error('Erro ao redefinir senha', [
                'error' => $e->getMessage(),
                'email' => $request->email ?? null
            ]);

            return back()->withErrors([
                'error' => 'Erro ao redefinir sua senha. Por favor, tente novamente.'
            ]);
        }
    }
} 