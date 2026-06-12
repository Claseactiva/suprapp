<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app-principal.css') }}" rel="stylesheet">

        <style>
            body{
                background-color: #e3e3e3 !important;
            }
            .card{
                box-shadow: 0px 3px 10px -2px rgb(0 0 0 / 20%);
            }
        </style>
        
        
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-lg-12">
                    <div class="card text-white bg-success my-3">
                        <div class="card-body">
                            <h5 class="card-title">¡Formulario enviado correctamente!</h5>
                            <p class="card-text">Tu formulario a sido enviado con éxito con <b>N° {{ basename(request()->path()) }}</b>, no olvides enviar este número a tu vendedor.</p>
                            <p class="card-text">¡Gracias por cotizar con nosostros!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

