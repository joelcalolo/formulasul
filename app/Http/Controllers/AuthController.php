<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'country' => $request->country,
                'role' => $request->role ?? 'cliente',
            ]);

            Auth::login($user);

            // Se for uma requisição AJAX, retornar JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registrado com sucesso!',
                    'redirect' => route('dashboard')
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Registrado com sucesso!');
        } catch (\Exception $e) {
            // Se for uma requisição AJAX, retornar JSON com erro
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar conta. Tente novamente.',
                    'errors' => ['general' => ['Erro interno do servidor']]
                ], 500);
            }

            return back()->withErrors(['general' => 'Erro ao criar conta. Tente novamente.']);
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            // Se for uma requisição AJAX, retornar JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login realizado com sucesso!',
                    'redirect' => route('dashboard')
                ]);
            }
            
            return redirect()->route('dashboard')->with('success', 'Login realizado com sucesso!');
        }

        // Se for uma requisição AJAX, retornar JSON com erro
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'As credenciais fornecidas estão incorretas.',
                'errors' => ['email' => ['As credenciais fornecidas estão incorretas.']]
            ], 422);
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas estão incorretas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logout realizado com sucesso!');
    }
}