@component('mail::message')
# CDC Digital - Resgate do codigo IOS

Para realizar o download do CDC Digital, primeiro instale o aplicativo Itunes Store no seu Iphone (<a href="https://apps.apple.com/br/app/itunes-store/id915061235">clique aqui para instalar</a>), após instalado clique no link abaixo.

@component('mail::button', ['url' => $iosCode->link])
Instalar CDC Digital
@endcomponent

<small>
   <a href="{{ $iosCode->link }}">{{$iosCode->link}}</a>
</small>

@endcomponent