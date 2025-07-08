<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitação de Aluguel Recebida - Fórmula Sul</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; color: #232323; margin: 0; padding: 0; }
        .email-container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px #e8e8ff; }
        .header { background: linear-gradient(135deg, #9494ea 0%, #7c7cd6 100%); color: #fff; text-align: center; padding: 32px 20px 20px 20px; }
        .header img { max-width: 120px; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 2rem; letter-spacing: 1px; }
        .content { padding: 32px 24px; background: #f9f9f9; text-align: center; }
        .section-title { color: #9494ea; font-size: 1.2rem; font-weight: bold; margin-bottom: 18px; text-align: center; }
        .info-row { margin-bottom: 14px; text-align: center; }
        .label { font-weight: bold; color: #9494ea; display: inline-block; min-width: 140px; text-align: right; }
        .value { color: #232323; display: inline-block; text-align: left; min-width: 120px; }
        .footer { background: #e8e8ff; color: #232323; text-align: center; padding: 18px 10px; font-size: 15px; }
        .footer strong { color: #7c7cd6; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Fórmula Sul">
            <h1>Fórmula Sul</h1>
            <p style="margin-top: 8px; font-size: 1.1rem;">@if($isAdminNotification)Nova Solicitação de Aluguel @else Solicitação Recebida @endif</p>
        </div>
        <div class="content">
            @if($isAdminNotification)
                <div class="section-title">Detalhes da Reserva</div>
            @else
                <p style="text-align: center;">Recebemos sua solicitação de aluguel e ela está sendo analisada. Em breve entraremos em contato!</p>
                <div class="section-title">Detalhes da Reserva</div>
            @endif
            <div class="info-row"><span class="label">Carro:</span> <span class="value">{{ $rentalRequest->car->marca }} {{ $rentalRequest->car->modelo }}</span></div>
            <div class="info-row"><span class="label">Data de Início:</span> <span class="value">{{ \Carbon\Carbon::parse($rentalRequest->data_inicio)->format('d/m/Y') }}</span></div>
            <div class="info-row"><span class="label">Data de Fim:</span> <span class="value">{{ \Carbon\Carbon::parse($rentalRequest->data_fim)->format('d/m/Y') }}</span></div>
            <div class="info-row"><span class="label">Local de Entrega:</span> <span class="value">{{ $rentalRequest->local_entrega }}</span></div>
            @if($isAdminNotification)
                <div class="section-title" style="margin-top: 28px;">Dados do Cliente</div>
                <div class="info-row"><span class="label">Nome:</span> <span class="value">{{ $rentalRequest->user->name }}</span></div>
                <div class="info-row"><span class="label">Email:</span> <span class="value">{{ $rentalRequest->user->email }}</span></div>
                <div class="info-row"><span class="label">Telefone:</span> <span class="value">{{ $rentalRequest->user->phone ?? '-' }}</span></div>
            @endif
        </div>
        <div class="footer">
            <strong>Fórmula Sul</strong> &mdash; Sua escolha para mobilidade com qualidade<br>
            📧 contato@formulasul.com | 📱 +244 953 429 189
        </div>
    </div>
</body>
</html> 