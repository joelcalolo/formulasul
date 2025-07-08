@component('mail::message')
# Recuperação de Senha

Olá!

Você está recebendo este e-mail porque recebemos uma solicitação de recuperação de senha para sua conta.

@component('mail::button', ['url' => $resetUrl, 'color' => 'primary'])
Redefinir Senha
@endcomponent

Este link de recuperação de senha expirará em 60 minutos.

Se você não solicitou uma recuperação de senha, nenhuma ação é necessária.

Atenciosamente,<br>
{{ config('app.name') }}

@component('mail::subcopy')
Se você estiver tendo problemas para clicar no botão "Redefinir Senha", copie e cole a URL abaixo em seu navegador: {{ $resetUrl }}
@endcomponent
@endcomponent 