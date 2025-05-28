<!-- resources/views/admin/transfers.blade.php -->
@extends('layouts.app')

@section('title', 'Gerenciar Transfers')

@section('content')
<!-- Hero -->
<div class="hero flex items-center justify-center text-white">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-center">Gerenciar Transfers</h1>
        <p class="mt-4 text-center">Visualize e confirme solicitações de transfer/passeio.</p>
    </div>
</div>

<!-- Seção Lista de Transfers -->
<section class="py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl text-center">Solicitações de Transfer/Passeio</h2>
        <div class="mt-10">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origem</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destino</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="transfers-table">
                        <!-- Transfers will be populated via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Função para buscar transfers via API
    async function fetchTransfers() {
        try {
            const response = await fetch('/api/transfers/all', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ Auth::user()->createToken('api')->plainTextToken }}'
                }
            });
            if (!response.ok) {
                throw new Error(`Erro ${response.status}: ${response.statusText}`);
            }
            const transfers = await response.json();
            populateTable(transfers);
        } catch (error) {
            console.error('Erro ao buscar transfers:', error);
            alert('Erro ao carregar solicitações. Verifique sua conexão ou permissões.');
        }
    }

    // Função para popular a tabela com transfers
    function populateTable(transfers) {
        const tableBody = document.getElementById('transfers-table');
        tableBody.innerHTML = '';
        transfers.forEach(transfer => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transfer.id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transfer.user_id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transfer.origem}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transfer.destino}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transfer.data_hora}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transfer.tipo}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transfer.status}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${transfer.status === 'pendente' ? 
                        `<button onclick="confirmTransfer(${transfer.id})" class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90">Confirmar</button>` 
                        : '-'}
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Função para confirmar um transfer
    async function confirmTransfer(id) {
        if (!confirm('Deseja confirmar esta solicitação de transfer/passeio?')) return;
        try {
            const response = await fetch(`/api/transfers/${id}/confirm`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ Auth::user()->createToken('api')->plainTextToken }}'
                }
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.error || `Erro ${response.status}`);
            }
            alert('Transfer confirmado com sucesso!');
            fetchTransfers(); // Atualiza a tabela
        } catch (error) {
            console.error('Erro ao confirmar transfer:', error);
            alert(`Erro: ${error.message}`);
        }
    }

    // Carregar transfers ao iniciar
    document.addEventListener('DOMContentLoaded', fetchTransfers);
</script>
@endsection