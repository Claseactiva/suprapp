<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <title>Envio N°{{ $shipping->id }}</title>

        <style>
            @page { margin: 10px 0 10px 0;  }
            table{
                width: 100%;
                font-size: 12px;
                text-transform: uppercase;
                font-weight: bold;
            }
            th, td {
                vertical-align: top;
            }
            th {
                font-size: 11px;
            }
            td {
                font-size: 12px;
            }
            .cliente th,
            .cliente td {
                font-style: italic;
            }
        </style>

    </head>
    <body>
        @yield('content')         
    </body>
</html>
