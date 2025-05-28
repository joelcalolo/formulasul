<!-- Create Modal -->
<div id="create-modal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="modal-content bg-white rounded-xl w-full max-w-md mx-4 p-6 relative">
        <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600" onclick="closeCreateModal()">
            ✕
        </button>
        <h2 class="text-xl font-semibold text-[var(--primary)] mb-4">Nova Solicitação</h2>
        <form id="create-form" method="POST" action="{{ route('transfers.store') }}">
            @csrf
            <x-form.input name="origem" label="Origem" required />
            <x-form.input name="destino" label="Destino" required />
            <x-form.input name="data_hora" label="Data e Hora" type="datetime-local" required />
            <x-form.select name="tipo" label="Tipo" :options="['aeroporto' => 'Aeroporto', 'cidade' => 'Cidade', 'intermunicipal' => 'Intermunicipal']" required />
            <x-form.textarea name="observacoes" label="Observações" rows="3" />
            <div class="flex justify-end mt-4 space-x-3">
                <button type="button" onclick="closeCreateModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="bg-[var(--secondary)] text-white px-4 py-2 rounded hover:bg-[var(--secondary)]/90">Solicitar</button>
            </div>
        </form>
    </div>
</div>
