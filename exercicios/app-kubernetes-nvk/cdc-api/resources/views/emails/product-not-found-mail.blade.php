@component('mail::message')
# Nova resposta - Não encontrei o produto

> {{ $user }}

> *{{ $message }}*

Atenciosamente,<br>
{{ config('app.name') }}
<br><br>
* {{ now() }} *

@endcomponent
