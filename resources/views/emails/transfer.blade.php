<h1>Nova Solicitação de Transfer/Passeio</h1>
<p>Cliente: {{ $transfer->user->name }}</p>
<p>Origem: {{ $transfer->origem }}</p>
<p>Destino: {{ $transfer->destino }}</p>
<p>Data e Hora: {{ $transfer->data_hora }}</p>
<p>Tipo: {{ $transfer->tipo }}</p>
<p>Observações: {{ $transfer->observacoes ?? 'Nenhuma' }}</p>