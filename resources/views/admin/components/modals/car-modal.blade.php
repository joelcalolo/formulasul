<!-- Modal para Adicionar Carro -->
<div id="car-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Adicionar Novo Carro</h3>
                <button onclick="closeCarModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="car-form" action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="-mb-px flex space-x-8 overflow-x-auto">
                        <button type="button" class="tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap" onclick="showTab('basic-info')">
                            Informações Básicas
                        </button>
                        <button type="button" class="tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap" onclick="showTab('specifications')">
                            Especificações
                        </button>
                        <button type="button" class="tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap" onclick="showTab('pricing')">
                            Preços
                        </button>
                        <button type="button" class="tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap" onclick="showTab('images')">
                            Imagens
                        </button>
                        <button type="button" class="tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap" onclick="showTab('details')">
                            Detalhes
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div id="basic-info" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="marca" class="block text-sm font-medium text-gray-700 mb-2">Marca *</label>
                            <input type="text" id="marca" name="marca" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="modelo" class="block text-sm font-medium text-gray-700 mb-2">Modelo *</label>
                            <input type="text" id="modelo" name="modelo" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status" name="status" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="disponivel">Disponível</option>
                                <option value="indisponivel">Indisponível</option>
                                <option value="manutencao">Em Manutenção</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="specifications" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="caixa" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Caixa *</label>
                            <select id="caixa" name="caixa" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione</option>
                                <option value="Manual">Manual</option>
                                <option value="Automática">Automática</option>
                            </select>
                        </div>
                        <div>
                            <label for="tracao" class="block text-sm font-medium text-gray-700 mb-2">Tração *</label>
                            <select id="tracao" name="tracao" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione</option>
                                <option value="Simples">Simples</option>
                                <option value="4X4">4X4</option>
                            </select>
                        </div>
                        <div>
                            <label for="lugares" class="block text-sm font-medium text-gray-700 mb-2">Número de Lugares *</label>
                            <input type="number" id="lugares" name="lugares" min="1" max="15" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="combustivel" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Combustível *</label>
                            <select id="combustivel" name="combustivel" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione</option>
                                <option value="Gasolina">Gasolina</option>
                                <option value="Gasóleo">Gasóleo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="pricing" class="tab-content hidden">
                    <div class="bg-blue-50 p-4 rounded-lg mb-4">
                        <h4 class="font-medium text-blue-900 mb-2">Tabela de Preços</h4>
                        <p class="text-sm text-blue-700">Configure os preços para diferentes cenários de aluguel</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Dentro da cidade -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h5 class="font-medium text-gray-800 mb-3">Dentro da Cidade</h5>
                            <div class="space-y-3">
                                <div>
                                    <label for="preco_dentro_com_motorista" class="block text-sm font-medium text-gray-700 mb-1">Com Motorista (Kz) *</label>
                                    <input type="number" id="preco_dentro_com_motorista" name="preco_dentro_com_motorista" min="0" step="0.01" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="preco_dentro_sem_motorista" class="block text-sm font-medium text-gray-700 mb-1">Sem Motorista (Kz) *</label>
                                    <input type="number" id="preco_dentro_sem_motorista" name="preco_dentro_sem_motorista" min="0" step="0.01" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Fora da cidade -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h5 class="font-medium text-gray-800 mb-3">Fora da Cidade</h5>
                            <div class="space-y-3">
                                <div>
                                    <label for="preco_fora_com_motorista" class="block text-sm font-medium text-gray-700 mb-1">Com Motorista (Kz) *</label>
                                    <input type="number" id="preco_fora_com_motorista" name="preco_fora_com_motorista" min="0" step="0.01" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="preco_fora_sem_motorista" class="block text-sm font-medium text-gray-700 mb-1">Sem Motorista (Kz) *</label>
                                    <input type="number" id="preco_fora_sem_motorista" name="preco_fora_sem_motorista" min="0" step="0.01" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Extras -->
                        <div class="bg-yellow-50 p-4 rounded-lg md:col-span-2">
                            <h5 class="font-medium text-yellow-900 mb-3">Configurações Extras</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="taxa_entrega_recolha" class="block text-sm font-medium text-gray-700 mb-1">Taxa Entrega/Recolha (Kz)</label>
                                    <input type="number" id="taxa_entrega_recolha" name="taxa_entrega_recolha" min="0" step="0.01" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="plafond_km_dia" class="block text-sm font-medium text-gray-700 mb-1">Plafond KM/Dia</label>
                                    <input type="number" id="plafond_km_dia" name="plafond_km_dia" min="1" value="100" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="preco_km_extra" class="block text-sm font-medium text-gray-700 mb-1">Preço KM Extra (Kz)</label>
                                    <input type="number" id="preco_km_extra" name="preco_km_extra" min="0" step="0.01" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="md:col-span-3">
                                    <label for="caucao" class="block text-sm font-medium text-gray-700 mb-1">Caução (Kz)</label>
                                    <input type="number" id="caucao" name="caucao" min="0" step="0.01" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="images" class="tab-content hidden">
                    <div class="space-y-4">
                        <div>
                            <label for="image_cover" class="block text-sm font-medium text-gray-700 mb-2">Imagem Principal *</label>
                            <input type="file" id="image_cover" name="image_cover" accept="image/*" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Esta será a imagem de capa do carro</p>
                        </div>
                        
                        <div>
                            <label for="image_1" class="block text-sm font-medium text-gray-700 mb-2">Imagem Adicional 1</label>
                            <input type="file" id="image_1" name="image_1" accept="image/*" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="image_2" class="block text-sm font-medium text-gray-700 mb-2">Imagem Adicional 2</label>
                            <input type="file" id="image_2" name="image_2" accept="image/*" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="image_3" class="block text-sm font-medium text-gray-700 mb-2">Imagem Adicional 3</label>
                            <input type="file" id="image_3" name="image_3" accept="image/*" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="image-preview-container" id="image-preview-container">
                            <!-- Previews serão inseridos aqui -->
                        </div>
                    </div>
                </div>

                <div id="details" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="cor" class="block text-sm font-medium text-gray-700 mb-2">Cor</label>
                            <input type="text" id="cor" name="cor" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="transmissao" class="block text-sm font-medium text-gray-700 mb-2">Transmissão</label>
                            <select id="transmissao" name="transmissao" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione</option>
                                <option value="manual">Manual</option>
                                <option value="automatico">Automático</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="4" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Descreva as características do carro..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCarModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Adicionar Carro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 