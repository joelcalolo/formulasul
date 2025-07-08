<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Confirmada - Formula Sul</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #9494ea 0%, #7c7cd6 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .info-row {
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            border-left: 4px solid #9494ea;
        }
        .label {
            font-weight: bold;
            color: #9494ea;
            display: inline-block;
            width: 120px;
        }
        .value {
            color: #333;
        }
        .success-section {
            background: #e8e8ff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .success-title {
            color: #9494ea;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .steps-list {
            list-style: none;
            padding: 0;
        }
        .steps-list li {
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .steps-list li:last-child {
            border-bottom: none;
        }
        .btn {
            display: inline-block;
            background: #9494ea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }
        .btn:hover {
            background: #7c7cd6;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .caucao-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
        .caucao-info strong {
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Formula Sul</h1>
        <p>✅ Sua Reserva foi Confirmada!</p>
    </div>
    
    <div class="content">
        <p>Temos o prazer de informar que sua solicitação de aluguel foi confirmada com sucesso!</p>
        
        <div class="info-row">
            <span class="label">Carro:</span>
            <span class="value">{{ $rentalRequest->car->marca ?? 'N/A' }} {{ $rentalRequest->car->modelo ?? 'N/A' }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Data de Início:</span>
            <span class="value">{{ $rentalRequest->data_inicio ? \Carbon\Carbon::parse($rentalRequest->data_inicio)->format('d/m/Y') : 'N/A' }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Data de Fim:</span>
            <span class="value">{{ $rentalRequest->data_fim ? \Carbon\Carbon::parse($rentalRequest->data_fim)->format('d/m/Y') : 'N/A' }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Local de Entrega:</span>
            <span class="value">{{ $rentalRequest->local_entrega ?? 'Não especificado' }}</span>
        </div>
        
        @if($rentalRequest->car && $rentalRequest->car->priceTable && $rentalRequest->car->priceTable->caucao)
            <div class="caucao-info">
                <strong>💰 Valor da Caução:</strong> Kz {{ number_format($rentalRequest->car->priceTable->caucao, 2, ',', '.') }}
            </div>
        @endif
        
        <div class="success-section">
            <div class="success-title">📋 Próximos Passos</div>
            <ol class="steps-list">
                <li><strong>Documentação:</strong> Prepare os documentos necessários (CNH válida e documento de identificação)</li>
                @if($rentalRequest->car && $rentalRequest->car->priceTable && $rentalRequest->car->priceTable->caucao)
                    <li><strong>Caução:</strong> Tenha em mãos o valor da caução: Kz {{ number_format($rentalRequest->car->priceTable->caucao, 2, ',', '.') }}</li>
                @else
                    <li><strong>Caução:</strong> Entre em contato conosco para informações sobre a caução</li>
                @endif
                <li><strong>Retirada:</strong> Compareça ao local de retirada no horário agendado</li>
            </ol>
        </div>
        

        
        <div class="footer">
            <p><strong>Formula Sul</strong> - Sua escolha para mobilidade com qualidade</p>
            <p>📧 contato@formulasul.com | 📱 +244 953 429 189</p>
            <p>Se tiver alguma dúvida, não hesite em nos contatar.</p>
        </div>
    </div>
</body>
</html> 