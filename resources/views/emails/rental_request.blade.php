<h1>Nova Solicitação de Aluguel</h1>
<p>Cliente: {{ $rentalRequest->user->name }}</p>
<p>Carro Principal: {{ $rentalRequest->carroPrincipal->modelo }}</p>
<p>Carro Secundário: {{ $rentalRequest->carroSecundario ? $rentalRequest->carroSecundario->modelo : 'Nenhum' }}</p>
<p>Data Início: {{ $rentalRequest->data_inicio }}</p>
<p>Data Fim: {{ $rentalRequest->data_fim }}</p>
<p>Local de Entrega: {{ $rentalRequest->local_entrega }}</p>
<p>Observações: {{ $rentalRequest->observacoes ?? 'Nenhuma' }}</p>