// Funções globais do Dashboard

// Função para mostrar toast notifications
window.showToast = function(message, type = 'info') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
    };
    
    toast.className = `${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full opacity-0 flex items-center space-x-2`;
    toast.innerHTML = `
        <span class="font-bold">${icons[type]}</span>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);
    
    // Animar entrada
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    }, 100);
    
    // Remover após 4 segundos
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (container.contains(toast)) {
                container.removeChild(toast);
            }
        }, 300);
    }, 4000);
};

window.showTab = function(tabId) {
    let activeModal = null;
    const modals = ['car-modal', 'edit-car-modal'];
    for (const modalId of modals) {
        const modal = document.getElementById(modalId);
        if (modal && !modal.classList.contains('hidden')) {
            activeModal = modal;
            break;
        }
    }
    if (!activeModal) {
        console.error('Nenhum modal ativo encontrado');
        return;
    }
    const tabContents = activeModal.querySelectorAll('.tab-content');
    tabContents.forEach(tab => tab.classList.add('hidden'));
    const selectedTab = activeModal.querySelector(`#${tabId}`);
    if (selectedTab) {
        selectedTab.classList.remove('hidden');
    }
    const tabs = activeModal.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.classList.remove('active', 'border-blue-600', 'text-blue-600');
        tab.classList.add('text-gray-500');
    });
    const activeTab = activeModal.querySelector(`[onclick="showTab('${tabId}')"]`);
    if (activeTab) {
        activeTab.classList.add('active', 'border-blue-600', 'text-blue-600');
        activeTab.classList.remove('text-gray-500');
    }
};

window.initializeTabs = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        const firstTabContent = modal.querySelector('.tab-content');
        const firstTabButton = modal.querySelector('.tab');
        if (firstTabContent && firstTabButton) {
            firstTabContent.classList.remove('hidden');
            firstTabButton.classList.add('active', 'border-blue-600', 'text-blue-600');
            firstTabButton.classList.remove('text-gray-500');
        }
    }
};

window.openCarModal = function() {
    const modal = document.getElementById('car-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        window.initializeTabs('car-modal');
    } else {
        console.error('Modal de carro não encontrado');
    }
};

window.closeCarModal = function() {
    const modal = document.getElementById('car-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        const form = document.getElementById('car-form');
        if (form) form.reset();
        const previewContainer = document.getElementById('image-preview-container');
        if (previewContainer) previewContainer.innerHTML = '';
    }
};

window.closeEditCarModal = function() {
    const modal = document.getElementById('edit-car-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        const form = document.getElementById('edit-car-form');
        if (form) form.reset();
        const previewContainer = document.getElementById('edit-image-preview-container');
        if (previewContainer) previewContainer.innerHTML = '';
        const currentImagesContainer = document.getElementById('current-images-container');
        if (currentImagesContainer) currentImagesContainer.innerHTML = '';
    }
};

window.closeDeleteCarModal = function() {
    const modal = document.getElementById('delete-car-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
};

window.closeConfirmModal = function() {
    const modal = document.getElementById('confirm-reservation-modal');
    const content = modal.querySelector('.modal-content');
    if (modal && content) {
        // Animação de saída
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // Reset da animação
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 300);
    }
};

// Interceptar submit do formulário de confirmação de reserva
document.addEventListener('DOMContentLoaded', function() {
    const confirmForm = document.getElementById('confirm-reservation-form');
    if (confirmForm) {
        confirmForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('confirm-reservation-btn');
            const btnText = document.getElementById('confirm-reservation-btn-text');
            const loading = document.getElementById('confirm-reservation-loading');
            
            // Mostrar loading
            btn.disabled = true;
            btnText.textContent = 'Confirmando...';
            loading.classList.remove('hidden');
            
            // Fazer a requisição
            fetch(this.action, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                closeConfirmModal();
                if (data.success) {
                    showToast('Reserva confirmada com sucesso!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message || 'Erro ao confirmar reserva', 'error');
                }
            })
            .catch(error => {
                console.error('Erro ao confirmar reserva:', error);
                showToast('Erro ao confirmar reserva. Tente novamente.', 'error');
            })
            .finally(() => {
                // Restaurar botão
                btn.disabled = false;
                btnText.textContent = 'Confirmar';
                loading.classList.add('hidden');
            });
        });
    }
});

window.closeTransferConfirmModal = function() {
    const modal = document.getElementById('confirm-transfer-modal');
    const content = document.getElementById('confirm-transfer-content');
    if (modal && content) {
        // Animação de saída
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // Reset da animação
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 300);
    }
};

window.closeTransferRejectModal = function() {
    const modal = document.getElementById('reject-transfer-modal');
    const content = document.getElementById('reject-transfer-content');
    if (modal && content) {
        // Animação de saída
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // Reset da animação
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
            // Limpar campo de motivo
            const rejectReason = document.getElementById('reject_reason');
            if (rejectReason) rejectReason.value = '';
        }, 300);
    }
};

window.closeReservationDetailsModal = function() {
    const modal = document.getElementById('reservation-details-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
};

window.editCar = function(carId) {
    console.log('Editando carro ID:', carId);
    showLoading('Carregando dados do carro...');
    fetch(`/admin/cars/${carId}/edit`)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            hideLoading();
            console.log('Dados recebidos:', data);
            if (data.success) {
                const car = data.car;
                const priceTable = data.price_table;
                console.log('Carro:', car);
                console.log('Tabela de preços:', priceTable);
                // Preencher campos básicos do carro SEM forçar valores
                const carFields = {
                    'edit_marca': car.marca || '',
                    'edit_modelo': car.modelo || '',
                    'edit_price': car.price || '',
                    'edit_status': car.status || '',
                    'edit_caixa': car.caixa || '',
                    'edit_tracao': car.tracao || '',
                    'edit_lugares': car.lugares || '',
                    'edit_combustivel': car.combustivel || '',
                    'edit_cor': car.cor || '',
                    'edit_transmissao': car.transmissao || '',
                    'edit_descricao': car.descricao || ''
                };
                Object.keys(carFields).forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.value = carFields[fieldId];
                        console.log(`Campo ${fieldId} preenchido com:`, carFields[fieldId]);
                    } else {
                        console.warn(`Campo ${fieldId} não encontrado`);
                    }
                });
                // Preencher campos da tabela de preços
                if (priceTable) {
                    const priceFields = {
                        'edit_preco_dentro_com_motorista': priceTable.preco_dentro_com_motorista || '',
                        'edit_preco_dentro_sem_motorista': priceTable.preco_dentro_sem_motorista || '',
                        'edit_preco_fora_com_motorista': priceTable.preco_fora_com_motorista || '',
                        'edit_preco_fora_sem_motorista': priceTable.preco_fora_sem_motorista || '',
                        'edit_taxa_entrega_recolha': priceTable.taxa_entrega_recolha || '',
                        'edit_plafond_km_dia': priceTable.plafond_km_dia || '',
                        'edit_preco_km_extra': priceTable.preco_km_extra || '',
                        'edit_caucao': priceTable.caucao || ''
                    };
                    Object.keys(priceFields).forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field) {
                            field.value = priceFields[fieldId];
                            console.log(`Campo ${fieldId} preenchido com:`, priceFields[fieldId]);
                        } else {
                            console.warn(`Campo ${fieldId} não encontrado`);
                        }
                    });
                }
                const form = document.getElementById('edit-car-form');
                if (form) form.action = `/admin/cars/${carId}`;
                window.showCurrentImages(car);
                const modal = document.getElementById('edit-car-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    window.initializeTabs('edit-car-modal');
                    console.log('Modal de edição aberto com sucesso');
                    showSuccess('Sucesso!', 'Dados do carro carregados com sucesso.');
                } else {
                    console.error('Modal de edição não encontrado');
                    showError('Erro!', 'Modal de edição não encontrado.');
                }
            } else {
                console.error('Erro na resposta:', data.message);
                showError('Erro!', 'Erro ao carregar dados do carro: ' + data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Erro ao carregar dados do carro:', error);
            showError('Erro!', 'Erro ao carregar dados do carro');
        });
};

window.showCurrentImages = function(car) {
    const container = document.getElementById('current-images-container');
    if (!container) return;
    
    container.innerHTML = '';
    const images = [
        { field: 'image_cover', label: 'Principal' },
        { field: 'image_1', label: 'Adicional 1' },
        { field: 'image_2', label: 'Adicional 2' },
        { field: 'image_3', label: 'Adicional 3' }
    ];
    
    images.forEach((img, index) => {
        if (car[img.field]) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="/storage/${car[img.field]}" alt="${img.label}" class="w-full h-20 object-cover rounded">
                <span class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs px-1 rounded-br">${img.label}</span>
            `;
            container.appendChild(div);
        }
    });
};

window.confirmDelete = function(carId, carName) {
    confirmDelete(carName).then(confirmed => {
        if (confirmed) {
            const form = document.getElementById('delete-car-form');
            if (form) {
                form.action = `/admin/cars/${carId}`;
                showLoading('Excluindo carro...');
                
                fetch(form.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showSuccess('Sucesso!', 'Carro excluído com sucesso!');
                        location.reload();
                    } else {
                        showError('Erro!', data.message || 'Erro ao excluir carro');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Erro ao excluir carro:', error);
                    showError('Erro!', 'Erro ao excluir carro');
                });
            }
        }
    });
};

window.openConfirmModal = function(reservationId) {
    showLoading('Carregando detalhes da reserva...');
    
    fetch(`/rental-requests/${reservationId}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                const reservation = data.rental_request;
                const detailsElement = document.getElementById('reservation-details');
                const form = document.getElementById('confirm-reservation-form');
                
                if (detailsElement) {
                    detailsElement.innerHTML = `
                        <p><strong>Cliente:</strong> ${reservation.user.name}</p>
                        <p><strong>Carro:</strong> ${reservation.car.marca} ${reservation.car.modelo}</p>
                        <p><strong>Data:</strong> ${reservation.data_inicio ? new Date(reservation.data_inicio).toLocaleDateString('pt-BR') : 'Não definida'}</p>
                        <p><strong>Status:</strong> ${reservation.status}</p>
                    `;
                }
                
                if (form) form.action = `/rental-requests/${reservationId}/confirm`;
                
                const modal = document.getElementById('confirm-reservation-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    
                    // Animação de entrada
                    const content = modal.querySelector('.modal-content');
                    if (content) {
                        setTimeout(() => {
                            content.classList.remove('scale-95', 'opacity-0');
                            content.classList.add('scale-100', 'opacity-100');
                        }, 10);
                    }
                }
            } else {
                showToast('Erro ao carregar detalhes da reserva', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Erro ao carregar detalhes da reserva:', error);
            showToast('Erro ao carregar detalhes da reserva', 'error');
        });
};

window.openTransferConfirmModal = function(transferId) {
    showLoading('Carregando detalhes do transfer...');
    
    fetch(`/admin/transfers/${transferId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Não autorizado. Faça login novamente.');
                } else if (response.status === 403) {
                    throw new Error('Acesso negado. Você não tem permissão para acessar esta funcionalidade.');
                } else if (response.status === 404) {
                    throw new Error('Transfer não encontrado.');
                } else {
                    throw new Error(`Erro do servidor: ${response.status}`);
                }
            }
            return response.json();
        })
        .then(data => {
            hideLoading();
            if (data.success) {
                const transfer = data.transfer;
                const detailsElement = document.getElementById('confirm-transfer-details');
                const form = document.getElementById('confirm-transfer-form');
                
                if (detailsElement) {
                    detailsElement.innerHTML = `
                        <p><strong>Cliente:</strong> ${transfer.user.name}</p>
                        <p><strong>Origem:</strong> ${transfer.origem}</p>
                        <p><strong>Destino:</strong> ${transfer.destino}</p>
                        <p><strong>Data/Hora:</strong> ${transfer.data_hora ? new Date(transfer.data_hora).toLocaleString('pt-BR') : 'Não definida'}</p>
                        <p><strong>Número de Pessoas:</strong> ${transfer.num_pessoas}</p>
                        <p><strong>Tipo:</strong> ${transfer.tipo}</p>
                    `;
                }
                
                if (form) form.action = `/admin/transfers/${transferId}/confirm`;
                
                const modal = document.getElementById('confirm-transfer-modal');
                const content = document.getElementById('confirm-transfer-content');
                if (modal && content) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    
                    // Animação de entrada
                    setTimeout(() => {
                        content.classList.remove('scale-95', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }, 10);
                }
            } else {
                showError('Erro!', data.message || 'Erro ao carregar detalhes do transfer');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Erro ao carregar detalhes do transfer:', error);
            
            if (error.message.includes('Não autorizado') || error.message.includes('Acesso negado')) {
                showError('Erro de Acesso', error.message);
                // Redirecionar para login se necessário
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showError('Erro!', error.message || 'Erro ao carregar detalhes do transfer');
            }
        });
};

window.openTransferRejectModal = function(transferId) {
    showLoading('Carregando detalhes do transfer...');
    
    fetch(`/admin/transfers/${transferId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Não autorizado. Faça login novamente.');
                } else if (response.status === 403) {
                    throw new Error('Acesso negado. Você não tem permissão para acessar esta funcionalidade.');
                } else if (response.status === 404) {
                    throw new Error('Transfer não encontrado.');
                } else {
                    throw new Error(`Erro do servidor: ${response.status}`);
                }
            }
            return response.json();
        })
        .then(data => {
            hideLoading();
            if (data.success) {
                const transfer = data.transfer;
                const detailsElement = document.getElementById('reject-transfer-details');
                const form = document.getElementById('reject-transfer-form');
                
                if (detailsElement) {
                    detailsElement.innerHTML = `
                        <p><strong>Cliente:</strong> ${transfer.user.name}</p>
                        <p><strong>Origem:</strong> ${transfer.origem}</p>
                        <p><strong>Destino:</strong> ${transfer.destino}</p>
                        <p><strong>Data/Hora:</strong> ${transfer.data_hora ? new Date(transfer.data_hora).toLocaleString('pt-BR') : 'Não definida'}</p>
                        <p><strong>Número de Pessoas:</strong> ${transfer.num_pessoas}</p>
                        <p><strong>Tipo:</strong> ${transfer.tipo}</p>
                    `;
                }
                
                if (form) form.action = `/admin/transfers/${transferId}/reject`;
                
                const modal = document.getElementById('reject-transfer-modal');
                const content = document.getElementById('reject-transfer-content');
                if (modal && content) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    
                    // Animação de entrada
                    setTimeout(() => {
                        content.classList.remove('scale-95', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }, 10);
                }
            } else {
                showError('Erro!', data.message || 'Erro ao carregar detalhes do transfer');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Erro ao carregar detalhes do transfer:', error);
            
            if (error.message.includes('Não autorizado') || error.message.includes('Acesso negado')) {
                showError('Erro de Acesso', error.message);
                // Redirecionar para login se necessário
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                showError('Erro!', error.message || 'Erro ao carregar detalhes do transfer');
            }
        });
};

window.openReservationDetailsModal = function(reservationId) {
    showLoading('Carregando detalhes da reserva...');
    
    fetch(`/rental-requests/${reservationId}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                const reservation = data.rental_request;
                const contentElement = document.getElementById('reservation-details-content');
                
                if (contentElement) {
                    contentElement.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-3">Informações do Cliente</h4>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Nome:</span> ${reservation.user.name}</p>
                                    <p><span class="font-medium">Email:</span> ${reservation.user.email}</p>
                                    <p><span class="font-medium">Telefone:</span> ${reservation.user.phone || 'Não informado'}</p>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-3">Informações do Veículo</h4>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Marca/Modelo:</span> ${reservation.car.marca} ${reservation.car.modelo}</p>
                                    <p><span class="font-medium">Ano:</span> ${reservation.car.ano}</p>
                                    <p><span class="font-medium">Categoria:</span> ${reservation.car.categoria}</p>
                                    <p><span class="font-medium">Preço por dia:</span> Kz ${parseFloat(reservation.car.price).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <h4 class="font-semibold text-gray-800 mb-3">Detalhes da Reserva</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <p><span class="font-medium">Data de Início:</span></p>
                                        <p>${reservation.data_inicio ? new Date(reservation.data_inicio).toLocaleDateString('pt-BR') : 'Não definida'}</p>
                                    </div>
                                    <div>
                                        <p><span class="font-medium">Data de Fim:</span></p>
                                        <p>${reservation.data_fim ? new Date(reservation.data_fim).toLocaleDateString('pt-BR') : 'Não definida'}</p>
                                    </div>
                                    <div>
                                        <p><span class="font-medium">Status:</span></p>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${reservation.status === 'confirmado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                            ${reservation.status.charAt(0).toUpperCase() + reservation.status.slice(1)}
                                        </span>
                                    </div>
                                </div>
                                ${reservation.observacoes ? `
                                    <div class="mt-4">
                                        <p><span class="font-medium">Observações:</span></p>
                                        <p class="text-gray-600">${reservation.observacoes}</p>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }
                
                const modal = document.getElementById('reservation-details-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            } else {
                showError('Erro!', 'Erro ao carregar detalhes da reserva');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Erro ao carregar detalhes da reserva:', error);
            showError('Erro!', 'Erro ao carregar detalhes da reserva');
        });
};

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Event listener para o formulário de edição
    const editCarForm = document.getElementById('edit-car-form');
    if (editCarForm) {
        editCarForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Formulário de edição submetido');
            
            // Garantir que todos os campos sejam incluídos no FormData
            const formData = new FormData();
            
            // Adicionar todos os campos do formulário ao FormData
            const formFields = this.querySelectorAll('input, select, textarea');
            formFields.forEach(field => {
                if (field.name && field.type !== 'file') {
                    // Remover o prefixo 'edit_' dos nomes dos campos
                    const fieldName = field.name.replace('edit_', '');
                    formData.append(fieldName, field.value);
                    console.log(`Campo ${fieldName}: ${field.value}`);
                } else if (field.name && field.type === 'file' && field.files[0]) {
                    // Para arquivos, remover o prefixo 'edit_' também
                    const fieldName = field.name.replace('edit_', '');
                    formData.append(fieldName, field.files[0]);
                    console.log(`Arquivo ${fieldName}: ${field.files[0].name}`);
                }
            });
            
            // Adicionar CSRF token
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'PUT');
            console.log('Dados do formulário:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
            
            // Log dos valores específicos que podem estar causando problemas
            console.log('Valores específicos:');
            console.log('Caixa:', document.getElementById('edit_caixa')?.value);
            console.log('Tração:', document.getElementById('edit_tracao')?.value);
            console.log('Combustível:', document.getElementById('edit_combustivel')?.value);
            console.log('Status:', document.getElementById('edit_status')?.value);
            console.log('Marca:', document.getElementById('edit_marca')?.value);
            console.log('Modelo:', document.getElementById('edit_modelo')?.value);
            console.log('Lugares:', document.getElementById('edit_lugares')?.value);
            
            // Verificar se todos os campos obrigatórios estão preenchidos
            const requiredFields = [
                'edit_marca', 'edit_modelo', 'edit_caixa', 'edit_tracao', 
                'edit_lugares', 'edit_combustivel', 'edit_status',
                'edit_preco_dentro_com_motorista', 'edit_preco_dentro_sem_motorista',
                'edit_preco_fora_com_motorista', 'edit_preco_fora_sem_motorista'
            ];
            
            console.log('Verificação de campos obrigatórios:');
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                console.log(`${fieldId}: ${field?.value || 'NÃO ENCONTRADO'}`);
            });
            
            // Verificar se há campos vazios
            const emptyFields = [];
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field || !field.value.trim()) {
                    emptyFields.push(fieldId);
                }
            });
            
            if (emptyFields.length > 0) {
                console.error('Campos obrigatórios vazios:', emptyFields);
                showError('Erro!', 'Por favor, preencha todos os campos obrigatórios.');
                return;
            }
            
            fetch(this.action, {
                method: 'POST', // Mudando para POST porque estamos usando FormData com _method
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Resposta do servidor:', data);
                if (data.success) {
                    showSuccess('Sucesso!', 'Carro atualizado com sucesso!');
                    closeEditCarModal();
                    location.reload(); // Recarregar a página para mostrar as mudanças
                } else {
                    if (data.errors) {
                        console.log('Erros de validação detalhados:', data.errors);
                        const errorMessages = [];
                        Object.keys(data.errors).forEach(field => {
                            data.errors[field].forEach(error => {
                                errorMessages.push(`${field}: ${error}`);
                            });
                        });
                        showError('Erros de Validação', errorMessages.join('\n'));
                    } else {
                        showError('Erro!', 'Erro ao atualizar carro: ' + (data.message || 'Erro desconhecido'));
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao atualizar carro:', error);
                showError('Erro!', 'Erro ao atualizar carro');
            });
        });
    }

    // Preview de imagens para o modal de adicionar carro
    const imageInputs = ['image_cover', 'image_1', 'image_2', 'image_3'];
    const previewContainer = document.getElementById('image-preview-container');
    
    imageInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && previewContainer) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.createElement('div');
                        preview.className = 'image-preview';
                        preview.innerHTML = `
                            <img src="${e.target.result}" alt="Preview">
                            <button type="button" class="remove-image" onclick="this.parentElement.remove()">×</button>
                        `;
                        previewContainer.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    // Preview de imagens para o modal de editar carro
    const editImageInputs = ['edit_image_cover', 'edit_image_1', 'edit_image_2', 'edit_image_3'];
    const editPreviewContainer = document.getElementById('edit-image-preview-container');
    
    editImageInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && editPreviewContainer) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.createElement('div');
                        preview.className = 'image-preview';
                        preview.innerHTML = `
                            <img src="${e.target.result}" alt="Preview">
                            <button type="button" class="remove-image" onclick="this.parentElement.remove()">×</button>
                        `;
                        editPreviewContainer.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    // Capturar motivo da rejeição
    const rejectReasonInput = document.getElementById('reject_reason');
    const rejectReasonHidden = document.getElementById('reject_reason_input');
    if (rejectReasonInput && rejectReasonHidden) {
        rejectReasonInput.addEventListener('input', function() {
            rejectReasonHidden.value = this.value;
        });
    }

    // Preencher automaticamente o campo 'price' com o valor de 'preco_dentro_sem_motorista' ao criar carro
    const carForm = document.getElementById('car-form');
    if (carForm) {
        carForm.addEventListener('submit', function(e) {
            // Cria um input hidden para price se não existir
            let priceInput = document.getElementById('price');
            if (!priceInput) {
                priceInput = document.createElement('input');
                priceInput.type = 'hidden';
                priceInput.id = 'price';
                priceInput.name = 'price';
                carForm.appendChild(priceInput);
            }
            // Pega o valor do campo preco_dentro_sem_motorista
            const precoDentroSemMotorista = document.getElementById('preco_dentro_sem_motorista');
            if (precoDentroSemMotorista) {
                priceInput.value = precoDentroSemMotorista.value;
            }
        });
    }

    // Tratamento do formulário de confirmação de transfer
    const confirmTransferForm = document.getElementById('confirm-transfer-form');
    if (confirmTransferForm) {
        confirmTransferForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Mostrar loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Confirmando...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Feedback visual de sucesso
                    submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Confirmado!';
                    submitBtn.className = 'px-4 py-2 bg-green-600 text-white rounded-md cursor-not-allowed';
                    
                    // Mostrar mensagem de sucesso
                    showSuccess('Sucesso!', data.message || 'Transfer confirmado com sucesso!');
                    
                    // Fechar modal após 2 segundos
                    setTimeout(() => {
                        closeTransferConfirmModal();
                        location.reload(); // Recarregar para atualizar a lista
                    }, 2000);
                } else {
                    // Restaurar botão em caso de erro
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    showError('Erro!', data.message || 'Erro ao confirmar transfer');
                }
            })
            .catch(error => {
                console.error('Erro ao confirmar transfer:', error);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                showError('Erro!', 'Erro ao confirmar transfer');
            });
        });
    }

    // Tratamento do formulário de rejeição de transfer
    const rejectTransferForm = document.getElementById('reject-transfer-form');
    if (rejectTransferForm) {
        rejectTransferForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            const rejectReason = document.getElementById('reject_reason').value;
            
            // Mostrar loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Rejeitando...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    reject_reason: rejectReason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Feedback visual de sucesso
                    submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Rejeitado!';
                    submitBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-md cursor-not-allowed';
                    
                    // Mostrar mensagem de sucesso
                    showSuccess('Sucesso!', data.message || 'Transfer rejeitado com sucesso!');
                    
                    // Fechar modal após 2 segundos
                    setTimeout(() => {
                        closeTransferRejectModal();
                        location.reload(); // Recarregar para atualizar a lista
                    }, 2000);
                } else {
                    // Restaurar botão em caso de erro
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    showError('Erro!', data.message || 'Erro ao rejeitar transfer');
                }
            })
            .catch(error => {
                console.error('Erro ao rejeitar transfer:', error);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                showError('Erro!', 'Erro ao rejeitar transfer');
            });
        });
    }
});

function showSuccess(title, message) {
    // Remover notificação anterior se existir
    const existingNotification = document.querySelector('.success-notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    const notification = document.createElement('div');
    notification.className = 'success-notification fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${title}</p>
                <p class="text-sm opacity-90">${message}</p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-white hover:text-green-200 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(notification);

    // Animação de entrada
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 10);

    // Auto-remover após 5 segundos
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

function showError(title, message) {
    // Remover notificação anterior se existir
    const existingNotification = document.querySelector('.error-notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    const notification = document.createElement('div');
    notification.className = 'error-notification fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${title}</p>
                <p class="text-sm opacity-90">${message}</p>
            </div>
            <div class="ml-auto pl-3">
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-white hover:text-red-200 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(notification);

    // Animação de entrada
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 10);

    // Auto-remover após 7 segundos (erros ficam mais tempo)
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 7000);
} 