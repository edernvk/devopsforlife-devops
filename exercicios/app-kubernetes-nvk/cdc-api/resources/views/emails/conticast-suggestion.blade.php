@component('mail::message')
# Sugestão de tema para Conti Cast

O usuário **{{ $name }}** (ID \#{{ $id }}) enviou a sugestão:

> *{{ $suggestion }}*

... para o Conti Cast pelo formulário na página do aplicativo.
<br><br>
Atenciosamente,<br>
{{ config('app.name') }}
<br><br>
*IP: {{$ip}} - {{ now() }}*

@endcomponent
