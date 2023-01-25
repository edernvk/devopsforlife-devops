@component('mail::message')
# Contato CDC Digital - Fale Conosco

**Nome**: {{ $name }}<br>
**E-mail**: {{ $email }}<br>
**Telefone**: {{ $mobile  }}
<br><br>
**Assunto**:
>*{{ $subject }}*<br>

**Conteúdo da mensagem**:
> *{{ $message }}*<br>
<br>

Atenciosamente,<br>
{{ config('app.name') }}
<br><br>
*IP: {{$ip}} - {{ now() }}*

@endcomponent
