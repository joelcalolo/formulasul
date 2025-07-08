<!-- Modal de Confirmação de Reserva -->
<div id="confirm-reservation-modal" class="modal fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-all duration-300">
    <div class="modal-content relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white transform transition-all duration-300 scale-95 opacity-0">
        <button onclick="closeConfirmModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar Reserva</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-600 mb-4">
                    Tem certeza que deseja confirmar esta reserva?
                </p>
                <p class="text-xs text-gray-500 mb-4">
                    Esta ação enviará um e-mail de confirmação ao cliente.
                </p>
                <div id="reservation-details" class="text-xs text-gray-600 mt-2 text-left bg-gray-50 p-3 rounded-lg">
                    <!-- Detalhes da reserva serão carregados aqui -->
                </div>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button onclick="closeConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-all duration-200 hover:scale-105">
                    Cancelar
                </button>
                <form id="confirm-reservation-form" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button id="confirm-reservation-btn" type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 hover:scale-105 flex items-center">
                        <span id="confirm-reservation-btn-text">Confirmar</span>
                        <div id="confirm-reservation-loading" class="hidden ml-2">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div> 