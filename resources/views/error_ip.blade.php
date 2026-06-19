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
        .abs-center {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}
</style>
        
    </head>
    <body>
        <div class="container-fluid  text-center abs-center">
            <div class="row">
                <div class="col">
                    <div class="display-3">¡El computador no esta registrado en el sistema!, Comuniquese con el administrador.</div>
                </div>
            </div>
        </div>
    </body>
</html>

