<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Solicitação de Transfer/Passeio</title>
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
            border-bottom: 2px solid #e74c3c;
        }
        .header h1 {
            color: #e74c3c;
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
            background-color: #ffc107;
            color: #856404;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .action-buttons {
            text-align: center;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            color: white;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-danger {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 Nova Solicitação de Transfer/Passeio</h1>
        </div>
        
        <div class="content">
            <p><strong>Uma nova solicitação de {{ ucfirst($transfer->tipo) }} foi recebida e requer sua atenção.</strong></p>
            
            <div class="details">
                <h3>👤 Informações do Cliente</h3>
                
                <div class="detail-row">
                    <span class="label">Nome:</span>
                    <span class="value">{{ $transfer->user->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">Email:</span>
                    <span class="value">{{ $transfer->user->email }}</span>
                </div>
                
                @if($transfer->user->phone)
                <div class="detail-row">
                    <span class="label">Telefone:</span>
                    <span class="value">{{ $transfer->user->phone }}</span>
                </div>
                @endif
                
                <h3>📋 Detalhes da Solicitação</h3>
                
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
                        <span class="status">Pendente</span>
                    </span>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="{{ url('/admin/transfers') }}" class="btn btn-primary">Ver no Painel Admin</a>
            </div>
            
            <p><strong>Ação necessária:</strong></p>
            <ul>
                <li>Revisar os detalhes da solicitação</li>
                <li>Verificar disponibilidade para a data solicitada</li>
                <li>Confirmar ou rejeitar a solicitação</li>
                <li>O cliente será notificado automaticamente</li>
            </ul>
        </div>
        
        <div class="footer">
            <p><strong>Fórmula Sul - Sistema de Notificações</strong></p>
            <p>Este email foi enviado automaticamente pelo sistema.</p>
        </div>
    </div>
</body>
</html> 