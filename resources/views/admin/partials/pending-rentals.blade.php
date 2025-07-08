@foreach($rentals as $rental)
<div class="flex justify-between items-center py-2 border-b border-gray-200">
    <div>
        <p class="text-sm text-gray-900">{{ $rental->user->name }}</p>
        <p class="text-xs text-gray-600">
            {{ $rental->carroPrincipal->marca }} {{ $rental->carroPrincipal->modelo }}
            ({{ $rental->data_inicio ? \Carbon\Carbon::parse($rental->data_inicio)->format('d/m/Y') : 'Data não definida' }})
        </p>
    </div>
    <button onclick="openConfirmModal({{ $rental->id }})" class="bg-[var(--secondary)] text-white px-4 py-1 rounded-md hover:bg-[var(--secondary)]/90">
        Confirmar
    </button>
</div>
@endforeach 