@extends('layouts.app')

@section('content')
    <div class="body text-center">
        <form class="form-signin" method="POST" action="{{ route('login', ['url' => $url]) }}">
            @csrf
            <img class="mb-4" src="/favicon.ico" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Por favor, registrese</h1>

            <label for="email" class="sr-only">Email</label>
            <input type="email" id="email" name="email"
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Email"
                value="{{ old('email') }}" autofocus>

            <label for="password" class="sr-only">Contraseña</label>
            <input type="password" id="password" name="password"
                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Contraseña">

            @if ($errors->has('error'))
                <div class="my-3">
                    <strong class="text-danger text-center">{{ $errors->first('error') }}</strong>
                </div>
            @endif
            <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar Sesion</button>
        </form>
    </div>
@endsection
<style>
    html,
    .body {
        height: 100%;
    }

    .body {
        display: -ms-flexbox;
        display: -webkit-box;
        display: flex;
        -ms-flex-align: center;
        -ms-flex-pack: center;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
    }

    .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }

    .form-signin .checkbox {
        font-weight: 400;
    }

    .form-signin .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
    }

    .form-signin .form-control:focus {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>
