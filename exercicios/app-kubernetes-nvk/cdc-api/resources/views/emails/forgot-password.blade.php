@component('mail::message')
# CDC Digital - Esqueci minha senha

Clique no botão abaixo ou navegue pelo link para alterar sua senha e acessar o CDC DIGITAL

@component('mail::button', ['url' => $url])
Alterar minha senha do CDC Digital
@endcomponent

<small>[app.casadiconti.com.br/recuperar-senha]({{ $url }})</small>

Se não solicitou esta alteração, desconsidere este e-mail.

@endcomponent
