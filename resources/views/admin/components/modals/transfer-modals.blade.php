<!-- Modal de Confirmação de Transfer -->
<div id="confirm-transfer-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-2xl rounded-lg bg-white transform transition-all duration-300 scale-95 opacity-0" id="confirm-transfer-content">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4 transform transition-all duration-300 hover:scale-110">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Confirmar Transfer</h3>
            <div class="mt-2 px-4 py-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-3">
                    Tem certeza que deseja confirmar este transfer?
                </p>
                <div id="confirm-transfer-details" class="text-xs text-gray-700 text-left space-y-1 bg-white p-3 rounded border">
                    <!-- Detalhes do transfer serão carregados aqui -->
                </div>
            </div>
            <div class="flex justify-center space-x-3 mt-6">
                <button onclick="closeTransferConfirmModal()" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200 font-medium">
                    Cancelar
                </button>
                <form id="confirm-transfer-form" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 font-medium transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Rejeição de Transfer -->
<div id="reject-transfer-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-2xl rounded-lg bg-white transform transition-all duration-300 scale-95 opacity-0" id="reject-transfer-content">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4 transform transition-all duration-300 hover:scale-110">
                <i class="fas fa-times text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Rejeitar Transfer</h3>
            <div class="mt-2 px-4 py-3 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-3">
                    Tem certeza que deseja rejeitar este transfer?
                </p>
                <div id="reject-transfer-details" class="text-xs text-gray-700 text-left space-y-1 bg-white p-3 rounded border mb-4">
                    <!-- Detalhes do transfer serão carregados aqui -->
                </div>
                <div class="mt-3">
                    <label for="reject_reason" class="block text-sm font-medium text-gray-700 mb-2 text-left">Motivo da Rejeição (opcional)</label>
                    <textarea id="reject_reason" name="reject_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" placeholder="Informe o motivo da rejeição..."></textarea>
                </div>
            </div>
            <div class="flex justify-center space-x-3 mt-6">
                <button onclick="closeTransferRejectModal()" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200 font-medium">
                    Cancelar
                </button>
                <form id="reject-transfer-form" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="reject_reason_input" name="reject_reason">
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-medium transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Rejeitar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div> 