<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Mensagem de Contato - Formula Sul</title>
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
        .passeio-section {
            background: #e8e8ff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .passeio-title {
            color: #9494ea;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Formula Sul</h1>
        <p>Nova Mensagem de Contato</p>
    </div>
    
    <div class="content">
        <div class="info-row">
            <span class="label">Nome:</span>
            <span class="value">{{ $data['name'] }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">{{ $data['email'] }}</span>
        </div>
        
        <div class="info-row">
            <span class="label">Telefone:</span>
            <span class="value">{{ $data['phone'] }}</span>
        </div>
        
        @if($data['tipo_contato'] === 'passeio')
            <div class="passeio-section">
                <div class="passeio-title">📋 Detalhes do Passeio</div>
                
                <div class="info-row">
                    <span class="label">Destino:</span>
                    <span class="value">{{ $data['destino_passeio'] }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Data:</span>
                    <span class="value">{{ $data['data_passeio'] }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Pessoas:</span>
                    <span class="value">{{ $data['pessoas'] }}</span>
                </div>
            </div>
        @endif
        
        @if(!empty($data['message']) && $data['message'] !== 'Nenhuma mensagem adicional')
            <div class="info-row">
                <span class="label">Mensagem:</span>
                <span class="value">{{ $data['message'] }}</span>
            </div>
        @endif
        
        <div class="footer">
            <p><strong>Formula Sul</strong> - Sua escolha para mobilidade com qualidade</p>
            <p>📧 contato@formulasul.com | 📱 +244 953 429 189</p>
            <p>Esta mensagem foi enviada através do formulário de contato do site.</p>
        </div>
    </div>
</body>
</html> 