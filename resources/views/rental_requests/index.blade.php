@extends('layouts.app')

@section('title', 'Minhas Solicitações')

@section('content')
<div class="hero flex items-center justify-center text-white">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-center">Minhas Solicitações de Aluguel</h1>
        @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                {{ session('success') }}
            </div>
        @endif
    </div>
</div>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="flex justify-end mb-4">
            <a href="{{ route('rental-requests.create') }}" class="bg-[var(--primary)] text-white px-4 py-2 rounded-md hover:bg-[var(--primary)]/90 transition">
                Nova Solicitação
            </a>
        </div>
        
        <div class="bg-white shadow rounded-lg overflow-hidden">
            @if($rentalRequests->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    Você ainda não fez nenhuma solicitação de aluguel.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <!-- Cabeçalho da tabela... (mantido igual) -->
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($rentalRequests as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->carroPrincipal->modelo ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $request->carroPrincipal->categoria ?? '' }}</div>
                                </td>
                                <!-- Restante das colunas... -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('rental-requests.show', $request->id) }}" class="text-[var(--primary)] hover:text-[var(--primary)]/80 mr-3">Detalhes</a>
                                    @if($request->status == 'pendente')
                                        <form action="{{ route('rental-requests.destroy', $request->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Tem certeza que deseja cancelar esta solicitação?')">Cancelar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4">
                    {{ $rentalRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection