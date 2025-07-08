<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\PriceTable;
use App\Models\RentalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    // Listar todos os carros
    public function index(Request $request)
    {
        try {
            $query = Car::with('priceTable');

            // Filtro de busca por marca ou modelo
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('marca', 'like', "%{$search}%")
                      ->orWhere('modelo', 'like', "%{$search}%");
                });
            }

            // Filtro por marca
            if ($request->has('marca') && $request->marca != '') {
                $query->where('marca', $request->marca);
            }

            // Filtro por tipo de serviço (afeta apenas a exibição do preço, não filtra os carros)
            // A lógica de preço está no JavaScript do frontend

            // Ordenação
            switch ($request->ordenar) {
                case 'preco_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'preco_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'disponibilidade':
                    $query->orderByRaw("CASE 
                        WHEN status = 'disponivel' THEN 1 
                        WHEN status = 'alugado' THEN 2 
                        ELSE 3 
                    END");
                    break;
                default: // recentes
                    $query->latest();
                    break;
            }

            $cars = $query->paginate(10);
            
            // Obter lista única de marcas para o filtro
            $marcas = Car::distinct()->pluck('marca')->sort();

            Log::info('Carros listados com sucesso', [
                'user_id' => $request->user() ? $request->user()->id : null,
                'count' => $cars->count(),
                'filters' => $request->only(['search', 'marca', 'tipo_servico', 'ordenar'])
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json($cars, 200);
            }

            return view('cars.index', [
                'cars' => $cars,
                'marcas' => $marcas
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao listar carros', [
                'error' => $e->getMessage(),
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            return back()->with('error', 'Erro ao listar carros');
        }
    }

    // Listar carros no dashboard do admin
    public function adminIndex(Request $request)
    {
        try {
            $cars = Car::with('priceTable')->paginate(10);

            Log::info('Carros listados no dashboard do admin', [
                'user_id' => $request->user()->id,
                'count' => $cars->count()
            ]);

            return view('admin.cars.index', [
                'cars' => $cars
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao listar carros no dashboard do admin', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id
            ]);

            return back()->with('error', 'Erro ao listar carros');
        }
    }

    // Mostrar detalhes de um carro específico
    public function show(Request $request, $id)
    {
        try {
            $car = Car::with('priceTable')->findOrFail($id); // Inclui a tabela de preços no carregamento

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

            return back()->with('error', 'Carro não encontrado');
        }
    }

    // Mostrar galeria de imagens de um carro
    public function gallery(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            
            // Retornar apenas a view da galeria para AJAX
            return view('cars.gallery', ['car' => $car]);
        } catch (\Exception $e) {
            Log::error('Erro ao carregar galeria do carro', [
                'car_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            return response()->json(['error' => 'Erro ao carregar galeria'], 500);
        }
    }

    // Criar um novo carro com tabela de preços
    public function store(Request $request)
    {
        try {
            $request->validate([
                'marca' => 'required|string|max:255',
                'modelo' => 'required|string|max:255',
                'caixa' => 'required|string|max:255',
                'tracao' => 'required|string|max:255',
                'lugares' => 'required|integer|min:1',
                'combustivel' => 'required|string|max:255',
                'status' => 'required|in:disponivel,alugado,manutencao',
                'image_cover' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'image_1' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'image_2' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'image_3' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'price' => 'required|numeric|min:0',
                'preco_dentro_com_motorista' => 'required|numeric|min:0',
                'preco_dentro_sem_motorista' => 'required|numeric|min:0',
                'preco_fora_com_motorista' => 'required|numeric|min:0',
                'preco_fora_sem_motorista' => 'required|numeric|min:0',
                'taxa_entrega_recolha' => 'required|numeric|min:0',
                'plafond_km_dia' => 'required|numeric|min:0',
                'preco_km_extra' => 'required|numeric|min:0',
                'caucao' => 'required|numeric|min:0'
            ]);

            $carData = $request->only([
                'marca', 'modelo', 'caixa', 'tracao', 'lugares',
                'combustivel', 'status', 'price'
            ]);

            // Processar imagem de capa
            if ($request->hasFile('image_cover')) {
                $image = $request->file('image_cover');
                $filename = time() . '_cover_' . $image->getClientOriginalName();
                $path = $image->storeAs('cars', $filename, 'public');
                $carData['image_cover'] = $path;
            }

            // Processar imagens adicionais
            $hasGallery = false;
            for ($i = 1; $i <= 3; $i++) {
                $imageField = "image_{$i}";
                if ($request->hasFile($imageField)) {
                    $image = $request->file($imageField);
                    $filename = time() . "_{$i}_" . $image->getClientOriginalName();
                    $path = $image->storeAs('cars', $filename, 'public');
                    $carData[$imageField] = $path;
                    $hasGallery = true;
                }
            }

            $carData['has_gallery'] = $hasGallery;

            $car = Car::create($carData);

            PriceTable::create([
                'car_id' => $car->id,
                'preco_dentro_com_motorista' => $request->preco_dentro_com_motorista,
                'preco_dentro_sem_motorista' => $request->preco_dentro_sem_motorista,
                'preco_fora_com_motorista' => $request->preco_fora_com_motorista,
                'preco_fora_sem_motorista' => $request->preco_fora_sem_motorista,
                'taxa_entrega_recolha' => $request->taxa_entrega_recolha,
                'plafond_km_dia' => $request->plafond_km_dia,
                'preco_km_extra' => $request->preco_km_extra,
                'caucao' => $request->caucao
            ]);

            return redirect()->route('dashboard')->with('success', 'Carro cadastrado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao cadastrar carro: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Erro ao cadastrar carro: ' . $e->getMessage());
        }
    }

    // Atualizar carro e tabela de preços
    public function update(Request $request, $id)
    {
        try {
            $car = Car::findOrFail($id);

            $request->validate([
                'marca' => 'required|string|max:255',
                'modelo' => 'required|string|max:255',
                'caixa' => 'required|string|max:255',
                'tracao' => 'required|string|max:255',
                'lugares' => 'required|integer|min:1',
                'combustivel' => 'required|string|max:255',
                'status' => 'required|in:disponivel,alugado,manutencao',
                'image_cover' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'image_1' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'image_2' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'image_3' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                'price' => 'required|numeric|min:0',
                'preco_dentro_com_motorista' => 'required|numeric|min:0',
                'preco_dentro_sem_motorista' => 'required|numeric|min:0',
                'preco_fora_com_motorista' => 'required|numeric|min:0',
                'preco_fora_sem_motorista' => 'required|numeric|min:0',
                'taxa_entrega_recolha' => 'required|numeric|min:0',
                'plafond_km_dia' => 'required|numeric|min:0',
                'preco_km_extra' => 'required|numeric|min:0',
                'caucao' => 'required|numeric|min:0'
            ]);

            $data = $request->except(['_token', '_method', 'image_cover', 'image_1', 'image_2', 'image_3']);

            // Processar imagem de capa
            if ($request->hasFile('image_cover')) {
                // Deletar imagem antiga se existir
                if ($car->image_cover && Storage::disk('public')->exists($car->image_cover)) {
                    Storage::disk('public')->delete($car->image_cover);
                }

                $image = $request->file('image_cover');
                $filename = time() . '_cover_' . $image->getClientOriginalName();
                $path = $image->storeAs('cars', $filename, 'public');
                $data['image_cover'] = $path;
            }

            // Processar imagens adicionais
            $hasGallery = false;
            for ($i = 1; $i <= 3; $i++) {
                $imageField = "image_{$i}";
                if ($request->hasFile($imageField)) {
                    // Deletar imagem antiga se existir
                    if ($car->$imageField && Storage::disk('public')->exists($car->$imageField)) {
                        Storage::disk('public')->delete($car->$imageField);
                    }

                    $image = $request->file($imageField);
                    $filename = time() . "_{$i}_" . $image->getClientOriginalName();
                    $path = $image->storeAs('cars', $filename, 'public');
                    $data[$imageField] = $path;
                    $hasGallery = true;
                }
            }

            // Verificar se já tem galeria ou se adicionou novas imagens
            if ($hasGallery || $car->has_gallery) {
                $data['has_gallery'] = true;
            }

            $car->update($data);

            $car->priceTable()->update([
                'preco_dentro_com_motorista' => $data['preco_dentro_com_motorista'],
                'preco_dentro_sem_motorista' => $data['preco_dentro_sem_motorista'],
                'preco_fora_com_motorista' => $data['preco_fora_com_motorista'],
                'preco_fora_sem_motorista' => $data['preco_fora_sem_motorista'],
                'taxa_entrega_recolha' => $data['taxa_entrega_recolha'],
                'plafond_km_dia' => $data['plafond_km_dia'],
                'preco_km_extra' => $data['preco_km_extra'],
                'caucao' => $data['caucao']
            ]);

            Log::info('Carro e tabela de preços atualizados com sucesso', [
                'car_id' => $car->id,
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            return redirect()->route('cars.index')->with('success', 'Carro atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar carro', [
                'car_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            return back()->withInput()->with('error', 'Erro ao atualizar carro: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $car = Car::with('priceTable')->findOrFail($id);
            
            return response()->json([
                'car' => $car,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao carregar dados do carro para edição', [
                'car_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados do carro'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $car = Car::findOrFail($id);

            // Verificar se o carro tem reservas ativas
            $hasActiveRentals = RentalRequest::where('carro_principal_id', $id)
                ->whereIn('status', ['pendente', 'confirmado'])
                ->exists();

            if ($hasActiveRentals) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível excluir um carro com reservas ativas.'
                ], 400);
            }

            // Deletar todas as imagens se existirem
            $imageFields = ['image_cover', 'image_1', 'image_2', 'image_3'];
            foreach ($imageFields as $field) {
                if ($car->$field && Storage::disk('public')->exists($car->$field)) {
                    Storage::disk('public')->delete($car->$field);
                }
            }

            // Deletar tabela de preços associada
            if ($car->priceTable) {
                $car->priceTable->delete();
            }

            // Deletar o carro
            $car->delete();

            Log::info('Carro excluído com sucesso', [
                'car_id' => $id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Carro excluído com sucesso!'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir carro', [
                'car_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir carro: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkAvailability(Request $request, $id)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date'
            ]);

            $car = Car::findOrFail($id);
            $isAvailable = $car->isAvailable($request->start_date, $request->end_date);

            Log::info('Verificação de disponibilidade realizada', [
                'car_id' => $id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_available' => $isAvailable,
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            return response()->json([
                'available' => $isAvailable,
                'message' => $isAvailable ? 'Carro disponível para o período selecionado' : 'Carro indisponível para o período selecionado'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao verificar disponibilidade do carro', [
                'car_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => $request->user() ? $request->user()->id : null
            ]);

            return response()->json([
                'available' => false,
                'message' => 'Erro ao verificar disponibilidade: ' . $e->getMessage()
            ], 500);
        }
    }
}