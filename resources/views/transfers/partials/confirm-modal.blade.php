<!-- Confirm Modal -->
<div id="confirm-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-xl w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600" onclick="closeConfirmModal()">
            ✕
        </button>
        <h2 class="text-xl font-semibold text-[var(--primary)] mb-4">Confirmar Transfer</h2>
        <p class="text-gray-600 mb-6">Tem certeza de que deseja confirmar esta solicitação?</p>
        <form id="confirm-form" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeConfirmModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="bg-[var(--secondary)] text-white px-4 py-2 rounded hover:bg-[var(--secondary)]/90">Confirmar</button>
            </div>
        </form>
    </div>
</div>
