@component('mail::message')
# Solicitação de Aluguel Rejeitada

Olá {{ $rentalRequest->user->name }},

Infelizmente, sua solicitação de aluguel foi rejeitada.

**Motivo da rejeição:**
> {{ $rentalRequest->reject_reason }}

Se tiver dúvidas ou quiser tentar novamente, entre em contato conosco.

Atenciosamente,
Equipe Fórmula Sul
@endcomponent 