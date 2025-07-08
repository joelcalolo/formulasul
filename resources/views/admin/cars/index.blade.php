@extends('layouts.app')

@section('title', 'Gerenciar Carros')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gerenciar Carros</h1>
        <a href="{{ route('cars.create') }}" class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90">
            Adicionar Carro
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagem</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carro</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço/dia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($cars as $car)
                <tr data-car-id="{{ $car->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="h-20 w-20">
                            @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" alt="{{ $car->marca }} {{ $car->modelo }}" class="h-full w-full object-cover rounded">
                            @else
                                <div class="h-full w-full bg-gray-200 flex items-center justify-center rounded">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $car->marca }} {{ $car->modelo }}</div>
                        <div class="text-sm text-gray-500">
                            {{ ucfirst($car->combustivel) }} • {{ ucfirst($car->caixa) }} • {{ $car->lugares }} lugares
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($car->status === 'disponivel') bg-green-100 text-green-800
                            @elseif($car->status === 'alugado') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($car->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        Kz {{ number_format($car->price, 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button onclick="editCar({{ $car->id }})" class="text-blue-600 hover:text-blue-900">
                            Editar
                        </button>
                        <button onclick="confirmDelete({{ $car->id }}, '{{ $car->marca }} {{ $car->modelo }}')" class="text-red-600 hover:text-red-900">
                            Excluir
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $cars->links() }}
    </div>
</div>

<!-- Modal de Edição -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Editar Carro</h2>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Abas -->
                <div class="tabs flex border-b mb-4">
                    <button type="button" class="edit-tab active px-4 py-2 text-[var(--primary)] font-semibold border-b-2 border-[var(--primary)]" onclick="showEditTab('car-details')">Dados do Carro</button>
                    <button type="button" class="edit-tab px-4 py-2 text-gray-500 hover:text-[var(--primary)]" onclick="showEditTab('price-table')">Tabela de Preços</button>
                </div>

                <div id="car-details" class="edit-tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                            <input type="text" name="marca" id="marca" class="w-full px-3 py-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                            <input type="text" name="modelo" id="modelo" class="w-full px-3 py-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="caixa" class="block text-sm font-medium text-gray-700">Caixa</label>
                            <select name="caixa" id="caixa" class="w-full px-3 py-2 border rounded" required>
                                <option value="manual">Manual</option>
                                <option value="automatica">Automática</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="tracao" class="block text-sm font-medium text-gray-700">Tração</label>
                            <select name="tracao" id="tracao" class="w-full px-3 py-2 border rounded" required>
                                <option value="Simples">Simples</option>
                                <option value="4X4">4X4</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="lugares" class="block text-sm font-medium text-gray-700">Lugares</label>
                            <input type="number" name="lugares" id="lugares" class="w-full px-3 py-2 border rounded" min="1" required>
                        </div>
                        <div class="mb-4">
                            <label for="combustivel" class="block text-sm font-medium text-gray-700">Combustível</label>
                            <select name="combustivel" id="combustivel" class="w-full px-3 py-2 border rounded" required>
                                <option value="gasolina">Gasolina</option>
                                <option value="diesel">Diesel</option>
                                <option value="eletrico">Elétrico</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border rounded" required>
                                <option value="disponivel">Disponível</option>
                                <option value="alugado">Alugado</option>
                                <option value="manutencao">Manutenção</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Preço por Dia</label>
                            <input type="number" name="price" id="price" class="w-full px-3 py-2 border rounded" step="0.01" min="0" required>
                        </div>
                        <div class="mb-4 col-span-2">
                            <label for="image" class="block text-sm font-medium text-gray-700">Imagem do Carro</label>
                            <div id="currentImage" class="mb-2"></div>
                            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg" class="w-full px-3 py-2 border rounded">
                            <p class="mt-1 text-sm text-gray-500">Formatos aceitos: JPG, JPEG, PNG. Tamanho máximo: 5MB</p>
                        </div>
                    </div>
                </div>

                <div id="price-table" class="edit-tab-content" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="preco_dentro_com_motorista" class="block text-sm font-medium text-gray-700">Dentro da Cidade (Com Motorista)</label>
                            <input type="number" name="preco_dentro_com_motorista" id="preco_dentro_com_motorista" class="w-full px-3 py-2 border rounded" step="0.01" required>
                        </div>
                        <div class="mb-4">
                            <label for="preco_dentro_sem_motorista" class="block text-sm font-medium text-gray-700">Dentro da Cidade (Sem Motorista)</label>
                            <input type="number" name="preco_dentro_sem_motorista" id="preco_dentro_sem_motorista" class="w-full px-3 py-2 border rounded" step="0.01" required>
                        </div>
                        <div class="mb-4">
                            <label for="preco_fora_com_motorista" class="block text-sm font-medium text-gray-700">Fora da Cidade (Com Motorista)</label>
                            <input type="number" name="preco_fora_com_motorista" id="preco_fora_com_motorista" class="w-full px-3 py-2 border rounded" step="0.01" required>
                        </div>
                        <div class="mb-4">
                            <label for="preco_fora_sem_motorista" class="block text-sm font-medium text-gray-700">Fora da Cidade (Sem Motorista)</label>
                            <input type="number" name="preco_fora_sem_motorista" id="preco_fora_sem_motorista" class="w-full px-3 py-2 border rounded" step="0.01" required>
                        </div>
                        <div class="mb-4">
                            <label for="taxa_entrega_recolha" class="block text-sm font-medium text-gray-700">Taxa de Entrega/Recolha</label>
                            <input type="number" name="taxa_entrega_recolha" id="taxa_entrega_recolha" class="w-full px-3 py-2 border rounded" step="0.01" required>
                        </div>
                        <div class="mb-4">
                            <label for="plafond_km_dia" class="block text-sm font-medium text-gray-700">Plafond de KM por Dia</label>
                            <input type="number" name="plafond_km_dia" id="plafond_km_dia" class="w-full px-3 py-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="preco_km_extra" class="block text-sm font-medium text-gray-700">Preço por KM Extra</label>
                            <input type="number" name="preco_km_extra" id="preco_km_extra" class="w-full px-3 py-2 border rounded" step="0.01" required>
                        </div>
                        <div class="mb-4">
                            <label for="caucao" class="block text-sm font-medium text-gray-700">Caução</label>
                            <input type="number" name="caucao" id="caucao" class="w-full px-3 py-2 border rounded" step="0.01" required>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md mx-4 p-6">
        <div class="text-center">
            <svg class="mx-auto mb-4 w-14 h-14 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 class="mb-5 text-lg font-normal text-gray-800">
                Tem certeza que deseja excluir o carro <span id="deleteCarName" class="font-semibold"></span>?
            </h3>
            <p class="mb-5 text-sm text-gray-600">
                Esta ação não pode ser desfeita.
            </p>
            <div class="flex justify-center space-x-4">
                <button type="button" onclick="closeDeleteModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Cancelar
                </button>
                <button type="button" onclick="deleteCar()" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                    Sim, excluir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Função para mostrar aba específica do modal de edição
    function showEditTab(tabId) {
        console.log('showEditTab chamada:', tabId);
        
        // Esconder todas as abas de conteúdo
        document.querySelectorAll('.edit-tab-content').forEach(tab => {
            tab.style.display = 'none';
        });
        
        // Mostrar a aba selecionada
        const selectedTab = document.getElementById(tabId);
        if (selectedTab) {
            selectedTab.style.display = 'block';
        }
        
        // Atualizar estado visual das abas
        document.querySelectorAll('.edit-tab').forEach(tab => {
            tab.classList.remove('active', 'border-[var(--primary)]', 'text-[var(--primary)]');
            tab.classList.add('text-gray-500');
        });
        
        // Ativar a aba clicada
        const activeTab = document.querySelector(`[onclick="showEditTab('${tabId}')"]`);
        if (activeTab) {
            activeTab.classList.add('active', 'border-[var(--primary)]', 'text-[var(--primary)]');
            activeTab.classList.remove('text-gray-500');
        }
    }

    function editCar(id) {
        fetch(`/cars/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                const car = data.car;
                const priceTable = car.price_table;

                // Preencher dados do carro
                document.getElementById('marca').value = car.marca;
                document.getElementById('modelo').value = car.modelo;
                document.getElementById('caixa').value = car.caixa;
                document.getElementById('tracao').value = car.tracao;
                document.getElementById('lugares').value = car.lugares;
                document.getElementById('combustivel').value = car.combustivel;
                document.getElementById('status').value = car.status;
                document.getElementById('price').value = car.price;

                // Mostrar imagem atual se existir
                const currentImageDiv = document.getElementById('currentImage');
                if (car.image) {
                    currentImageDiv.innerHTML = `
                        <img src="/storage/${car.image}" alt="Imagem atual do carro" class="w-32 h-32 object-cover rounded mb-2">
                        <p class="text-sm text-gray-500">Imagem atual</p>
                    `;
                } else {
                    currentImageDiv.innerHTML = '';
                }

                // Preencher dados da tabela de preços
                document.getElementById('preco_dentro_com_motorista').value = priceTable.preco_dentro_com_motorista;
                document.getElementById('preco_dentro_sem_motorista').value = priceTable.preco_dentro_sem_motorista;
                document.getElementById('preco_fora_com_motorista').value = priceTable.preco_fora_com_motorista;
                document.getElementById('preco_fora_sem_motorista').value = priceTable.preco_fora_sem_motorista;
                document.getElementById('taxa_entrega_recolha').value = priceTable.taxa_entrega_recolha;
                document.getElementById('plafond_km_dia').value = priceTable.plafond_km_dia;
                document.getElementById('preco_km_extra').value = priceTable.preco_km_extra;
                document.getElementById('caucao').value = priceTable.caucao;

                // Configurar formulário
                const form = document.getElementById('editForm');
                form.action = `/cars/${id}`;

                // Abrir modal
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editModal').classList.add('flex');
                
                // Ativar a primeira aba por padrão
                showEditTab('car-details');
            })
                    .catch(error => {
            console.error('Erro ao carregar dados do carro:', error);
            showToast('Erro ao carregar dados do carro. Por favor, tente novamente.', 'error');
        });
    }

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
        document.getElementById('editForm').reset();
    }

    let carToDelete = null;

    function confirmDelete(id, carName) {
        carToDelete = id;
        document.getElementById('deleteCarName').textContent = carName;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
        carToDelete = null;
    }

    function deleteCar() {
        if (!carToDelete) return;

        fetch(`/cars/${carToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fechar o modal
                closeDeleteModal();
                
                // Remover a linha da tabela
                const row = document.querySelector(`tr[data-car-id="${carToDelete}"]`);
                if (row) {
                    row.remove();
                }

                // Mostrar mensagem de sucesso
                const successAlert = document.createElement('div');
                successAlert.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
                successAlert.innerHTML = `
                    <span class="block sm:inline">${data.message}</span>
                `;
                
                // Inserir a mensagem no topo da página
                const container = document.querySelector('.container');
                container.insertBefore(successAlert, container.firstChild);

                // Remover a mensagem após 3 segundos
                setTimeout(() => {
                    successAlert.remove();
                }, 3000);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao excluir carro:', error);
            alert('Erro ao excluir carro. Por favor, tente novamente.');
        });
    }

    // Event listeners quando o DOM estiver carregado
    document.addEventListener('DOMContentLoaded', function() {
        // Fechar modais ao clicar fora deles
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    });
</script>
@endsection 