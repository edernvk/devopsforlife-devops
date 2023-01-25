<html>

<head>
    <style>
        h1,p {
            font-family: Arial, Helvetica, sans-serif
        }
    </style>
</head>

<body>
    <h1 style="font-family: Arial">{{ $file->name }}</h1>

    <p style="font-family: Arial">
        Assinado por: {{ $sing->user->name }}
    </p>

    <p style="font-family: Arial">
        IP: {{ $sing->ip }}
    </p>

    <p style="font-family: Arial">
        Em {{ $date }}
    </p>

    <p style="font-family: Arial">
        Hash da assinatura:
        {{ $sing->sing }}
    </p>
</body>

</html>
