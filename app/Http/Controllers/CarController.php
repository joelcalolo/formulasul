<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{   
    // Listar todos os carros
    public function index(Request $request)
    {
        try {
            $cars = Car::paginate(10); // Ajusta o número de itens por página conforme necessário

            Log::info('Carros listados com sucesso', [
                'user_id' => $request->user() ? $request->user()->id : null,
                'count' => $cars->count()
            ]);

            // Verifica se o cliente espera JSON (API)
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json($cars, 200);
            }

            // Retorno para web (Blade)
            return view('cars.index', [
                'cars' => $cars,
                'isAdmin' => $request->user() && $request->user()->hasRole('admin')
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao listar carros', [
                'error' => $e->getMessage(),
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Erro ao listar carros'], 500);
            }

            return back()->with('error', 'Erro ao listar carros');
        }
    }

    // Mostrar formulário de criação
    public function create()
    {
        return view('cars.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'modelo' => 'required|string|max:255',
                'categoria' => 'required|string|max:255',
                'status' => 'required|in:disponivel,alugado,manutencao',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'price' => 'required|numeric|min:0'
            ]);

            $data = $request->all();
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images/cars', 'public');
                $data['image'] = $imagePath;
            }

            Log::info('Dados enviados para criação do carro', $data);

            $car = Car::create($data);

            Log::info('Carro criado com sucesso', [
                'car_id' => $car->id,
                'modelo' => $car->modelo,
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            return redirect()->route('dashboard')->with('success', 'Carro criado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar carro', [
                'error' => $e->getMessage(),
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            return back()->withInput()->with('error', 'Erro ao criar carro');
        }
    }

    // Mostrar detalhes de um carro específico
    public function show(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json($car, 200);
            }

            return view('cars.show', ['car' => $car]);
        } catch (\Exception $e) {
            Log::error('Erro ao visualizar carro', [
                'car_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Carro não encontrado'], 404);
            }

            return back()->with('error', 'Carro não encontrado');
        }
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
        $car = Car::findOrFail($id);
        return view('cars.edit', ['car' => $car]);
    }

    public function update(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);

            $request->validate([
                'modelo' => 'required|string|max:255',
                'categoria' => 'required|string|max:255',
                'status' => 'required|in:disponivel,alugado,manutencao'
            ]);

            $car->update([
                'modelo' => $request->modelo,
                'categoria' => $request->categoria,
                'status' => $request->status
            ]);

            Log::info('Carro atualizado com sucesso', [
                'car_id' => $car->id,
                'modelo' => $car->modelo,
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json($car, 200);
            }

            return redirect()->route('cars.index')->with('success', 'Carro atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar carro', [
                'car_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Erro ao atualizar carro'], 500);
            }

            return back()->withInput()->with('error', 'Erro ao atualizar carro');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            $car->delete();

            Log::info('Carro deletado com sucesso', [
                'car_id' => $id,
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Carro deletado com sucesso'], 200);
            }

            return redirect()->route('cars.index')->with('success', 'Carro deletado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao deletar carro', [
                'car_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Erro ao deletar carro'], 500);
            }

            return back()->with('error', 'Erro ao deletar carro');
        }
    }
}