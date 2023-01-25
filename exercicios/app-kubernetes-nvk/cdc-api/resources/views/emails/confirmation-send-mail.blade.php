@component('mail::message')
# CDC-DIGITAL

Prezado(a) {{ $user->name }},

A sua mensagem dos produtos não encontrados foi enviada com sucesso e
encaminhada para o setor responsável.

Muito obrigado. Caso tenha mais alguma dúvida, nos colocamos à disposição.

Atenciosamente,<br>
{{ config('app.name') }}
<br><br>
* {{ now() }} *

@endcomponent
