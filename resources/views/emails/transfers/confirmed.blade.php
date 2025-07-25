<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer/Passeio Confirmado</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #28a745;
        }
        .header h1 {
            color: #28a745;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .value {
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .status {
            display: inline-block;
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .highlight {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Transfer/Passeio Confirmado!</h1>
        </div>
        
        <div class="content">
            <p>Olá <strong>{{ $transfer->user->name }}</strong>,</p>
            
            <div class="highlight">
                <p><strong>🎉 Excelente notícia!</strong> Sua solicitação de <strong>{{ ucfirst($transfer->tipo) }}</strong> foi confirmada pela nossa equipe.</p>
            </div>
            
            <div class="details">
                <h3>📋 Detalhes Confirmados</h3>
                
                <div class="detail-row">
                    <span class="label">Tipo:</span>
                    <span class="value">{{ ucfirst($transfer->tipo) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Origem:</span>
                    <span class="value">{{ $transfer->origem }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Destino:</span>
                    <span class="value">{{ $transfer->destino }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Data e Hora:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($transfer->data_hora)->format('d/m/Y H:i') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Número de Pessoas:</span>
                    <span class="value">{{ $transfer->num_pessoas }} {{ $transfer->num_pessoas == 1 ? 'pessoa' : 'pessoas' }}</span>
                </div>
                
                @if($transfer->flight_number)
                <div class="detail-row">
                    <span class="label">Número do Voo:</span>
                    <span class="value">{{ $transfer->flight_number }}</span>
                </div>
                @endif
                
                @if($transfer->observacoes)
                <div class="detail-row">
                    <span class="label">Observações:</span>
                    <span class="value">{{ $transfer->observacoes }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="label">Status:</span>
                    <span class="value">
                        <span class="status">Confirmado</span>
                    </span>
                </div>
                
                @if($transfer->confirmed_at)
                <div class="detail-row">
                    <span class="label">Confirmado em:</span>
                    <span class="value">{{ \Carbon\Carbon::parse($transfer->confirmed_at)->format('d/m/Y H:i') }}</span>
                </div>
                @endif
            </div>
            
            <p><strong>📞 Próximos passos:</strong></p>
            <ul>
                <li>Nossa equipe entrará em contato para confirmar os detalhes finais</li>
                <li>Mantenha seu telefone disponível para receber nossa ligação</li>
                <li>Em caso de alterações, entre em contato conosco imediatamente</li>
                <li>Chegue no local de partida com 10 minutos de antecedência</li>
            </ul>
            
            <p><strong>⚠️ Informações importantes:</strong></p>
            <ul>
                <li>Traga documentos de identificação</li>
                <li>Em caso de atraso, informe-nos o quanto antes</li>
                <li>Para cancelamentos, entre em contato com pelo menos 24h de antecedência</li>
            </ul>
        </div>
        
        <div class="footer">
            <p><strong>Fórmula Sul</strong></p>
            <p>📧 contato@formulasul.com | 📞 +244 123 456 789</p>
            <p>Obrigado por escolher nossos serviços!</p>
        </div>
    </div>
</body>
</html>
