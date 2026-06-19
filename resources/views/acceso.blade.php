<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">


    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app-principal.css') }}" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
        }

        #app {
            margin: auto;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        #app .form-signin {
            width: 100%;
            padding: 15px;
            margin: auto;
        }

        #app .form-signin .checkbox {
            font-weight: 400;
        }

        #app .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }

        #app .form-signin .form-control:focus {
            z-index: 2;
        }

        #app .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
    </style>
</head>

<body class="text-center">
    <div id="app">

        <div class="container">
            <main class="form-signin">
                <form method="POST" action="{{ url('acceso/user-id', ['url' => $url]) }}">
                    @csrf
                    @method('PUT')
                    <h1 class="h3 mb-3 fw-normal">Bienvenido a Suprapp, {{ old('title', $name) }}</h1>
                    <button type="submit" class="w-100 btn btn-lg btn-primary">Acceso a la APP</button>
                </form>
            </main>
        </div>

    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
