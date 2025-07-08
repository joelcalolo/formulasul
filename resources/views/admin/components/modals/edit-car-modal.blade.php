<!-- Modal para Editar Carro -->
<div id="edit-car-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Editar Carro</h3>
                <button onclick="closeEditCarModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="edit-car-form" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-4">
                    <nav class="-mb-px flex space-x-8 overflow-x-auto">
                        <button type="button" class="tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap" onclick="showTab('edit-basic-info')">
                            Informações Básicas
                        </button>
                        <button type="button" class="tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap" onclick="showTab('edit-specifications')">
                            Especificações
                        </button>
                        <button type="button" class="tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap" onclick="showTab('edit-pricing')">
                            Preços
                        </button>
                        <button type="button" class="tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap" onclick="showTab('edit-images')">
                            Imagens
                        </button>
                        <button type="button" class="tab py-2 px-1 border-b-2 font-medium text-sm whitespace-nowrap" onclick="showTab('edit-details')">
                            Detalhes
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div id="edit-basic-info" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_marca" class="block text-sm font-medium text-gray-700 mb-2">Marca *</label>
                            <input type="text" id="edit_marca" name="marca" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="edit_modelo" class="block text-sm font-medium text-gray-700 mb-2">Modelo *</label>
                            <input type="text" id="edit_modelo" name="modelo" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select id="edit_status" name="status" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="disponivel">Disponível</option>
                                <option value="indisponivel">Indisponível</option>
                                <option value="manutencao">Em Manutenção</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_price" class="block text-sm font-medium text-gray-700 mb-2">Preço Base por Dia (Kz)</label>
                            <input type="number" id="edit_price" name="price" min="0" step="0.01" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Preço padrão">
                            <p class="text-xs text-gray-500 mt-1">Preço base (será sobrescrito pela tabela de preços)</p>
                        </div>
                    </div>
                </div>

                <div id="edit-specifications" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_caixa" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Caixa *</label>
                            <select id="edit_caixa" name="caixa" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione</option>
                                <option value="Manual">Manual</option>
                                <option value="Automática">Automática</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_tracao" class="block text-sm font-medium text-gray-700 mb-2">Tração *</label>
                            <select id="edit_tracao" name="tracao" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione</option>
                                <option value="Simples">Simples</option>
                                <option value="4X4">4X4</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_lugares" class="block text-sm font-medium text-gray-700 mb-2">Número de Lugares *</label>
                            <input type="number" id="edit_lugares" name="lugares" min="1" max="15" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="edit_combustivel" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Combustível *</label>
                            <select id="edit_combustivel" name="combustivel" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione</option>
                                <option value="Gasolina">Gasolina</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Elétrico">Elétrico</option>
                                <option value="Híbrido">Híbrido</option>
                                <option value="GPL">GPL</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="edit-pricing" class="tab-content hidden">
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
                                    <label for="edit_preco_dentro_com_motorista" class="block text-sm font-medium text-gray-700 mb-1">Com Motorista (Kz) *</label>
                                    <input type="number" id="edit_preco_dentro_com_motorista" name="preco_dentro_com_motorista" min="0" step="0.01" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="edit_preco_dentro_sem_motorista" class="block text-sm font-medium text-gray-700 mb-1">Sem Motorista (Kz) *</label>
                                    <input type="number" id="edit_preco_dentro_sem_motorista" name="preco_dentro_sem_motorista" min="0" step="0.01" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Fora da cidade -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h5 class="font-medium text-gray-800 mb-3">Fora da Cidade</h5>
                            <div class="space-y-3">
                                <div>
                                    <label for="edit_preco_fora_com_motorista" class="block text-sm font-medium text-gray-700 mb-1">Com Motorista (Kz) *</label>
                                    <input type="number" id="edit_preco_fora_com_motorista" name="preco_fora_com_motorista" min="0" step="0.01" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="edit_preco_fora_sem_motorista" class="block text-sm font-medium text-gray-700 mb-1">Sem Motorista (Kz) *</label>
                                    <input type="number" id="edit_preco_fora_sem_motorista" name="preco_fora_sem_motorista" min="0" step="0.01" required class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Extras -->
                        <div class="bg-yellow-50 p-4 rounded-lg md:col-span-2">
                            <h5 class="font-medium text-yellow-900 mb-3">Configurações Extras</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="edit_taxa_entrega_recolha" class="block text-sm font-medium text-gray-700 mb-1">Taxa Entrega/Recolha (Kz)</label>
                                    <input type="number" id="edit_taxa_entrega_recolha" name="taxa_entrega_recolha" min="0" step="0.01" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="edit_plafond_km_dia" class="block text-sm font-medium text-gray-700 mb-1">Plafond KM/Dia</label>
                                    <input type="number" id="edit_plafond_km_dia" name="plafond_km_dia" min="1" value="100" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="edit_preco_km_extra" class="block text-sm font-medium text-gray-700 mb-1">Preço KM Extra (Kz)</label>
                                    <input type="number" id="edit_preco_km_extra" name="preco_km_extra" min="0" step="0.01" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="md:col-span-3">
                                    <label for="edit_caucao" class="block text-sm font-medium text-gray-700 mb-1">Caução (Kz)</label>
                                    <input type="number" id="edit_caucao" name="caucao" min="0" step="0.01" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="edit-images" class="tab-content hidden">
                    <div class="space-y-4">
                        <!-- Imagens Atuais -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Imagens Atuais</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" id="current-images-container">
                                <!-- Imagens atuais serão carregadas aqui -->
                            </div>
                        </div>

                        <div>
                            <label for="edit_image_cover" class="block text-sm font-medium text-gray-700 mb-2">Nova Imagem Principal</label>
                            <input type="file" id="edit_image_cover" name="image_cover" accept="image/*" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Deixe em branco para manter a imagem atual</p>
                        </div>
                        
                        <div>
                            <label for="edit_image_1" class="block text-sm font-medium text-gray-700 mb-2">Nova Imagem Adicional 1</label>
                            <input type="file" id="edit_image_1" name="image_1" accept="image/*" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="edit_image_2" class="block text-sm font-medium text-gray-700 mb-2">Nova Imagem Adicional 2</label>
                            <input type="file" id="edit_image_2" name="image_2" accept="image/*" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label for="edit_image_3" class="block text-sm font-medium text-gray-700 mb-2">Nova Imagem Adicional 3</label>
                            <input type="file" id="edit_image_3" name="image_3" accept="image/*" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="image-preview-container" id="edit-image-preview-container">
                            <!-- Previews serão inseridos aqui -->
                        </div>
                    </div>
                </div>

                <div id="edit-details" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_cor" class="block text-sm font-medium text-gray-700 mb-2">Cor</label>
                            <input type="text" id="edit_cor" name="cor" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="edit_transmissao" class="block text-sm font-medium text-gray-700 mb-2">Transmissão</label>
                            <select id="edit_transmissao" name="transmissao" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione</option>
                                <option value="manual">Manual</option>
                                <option value="automatico">Automático</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="edit_descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                            <textarea id="edit_descricao" name="descricao" rows="4" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Descreva as características do carro..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditCarModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Atualizar Carro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 