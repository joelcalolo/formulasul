<h1>Código de Confirmação</h1>
<p>Seu aluguel foi confirmado!</p>
<p>Código: {{ $rental->codigo_confirmacao }}</p>
<p>Carro: {{ $rental->car->modelo }}</p>
<p>Data Início: {{ $rental->data_inicio }}</p>
<p>Data Fim: {{ $rental->data_fim }}</p>
<p>Local de Entrega: {{ $rental->local_entrega }}</p>