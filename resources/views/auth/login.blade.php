@extends('layouts.app')

@section('content')
    <div class="body text-center">
        <form class="form-signin" method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <img class="mb-4" src="/favicon.ico" alt="" width="72" height="72">
            <h1 class="h3 mb-3 font-weight-normal">Por favor, registrese</h1>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <label for="email" class="sr-only">Email</label>
            <input type="email" id="email" name="email"
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Email"
                value="{{ old('email') }}" autofocus>

            <label for="password" class="sr-only">Contraseña</label>
            <div class="input-group">
                <input type="password" id="password" name="password"
                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Contraseña">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password" tabindex="-1">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <input type="hidden" id="device_fingerprint" name="device_fingerprint">
            <input type="hidden" id="device_name" name="device_name">

            @if ($errors->has('error'))
                <div class="my-3">
                    <strong class="text-danger text-center">{{ $errors->first('error') }}</strong>
                </div>
            @endif
            <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar Sesion</button>
            <a href="{{ route('password.request') }}" class="d-block mt-3">¿Olvidaste tu contraseña?</a>
        </form>
    </div>

    <script>
        (function () {
            var STORAGE_KEY = 'suprapp_device_fingerprint_v1';
            var fingerprint = '';
            try {
                fingerprint = window.localStorage.getItem(STORAGE_KEY) || '';
                if (!fingerprint) {
                    fingerprint = 'dev-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2);
                    window.localStorage.setItem(STORAGE_KEY, fingerprint);
                }
            } catch (e) {
                fingerprint = 'dev-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2);
            }

            var deviceName = (navigator.platform || 'Dispositivo') + ' · ' + (navigator.language || '');

            document.getElementById('device_fingerprint').value = fingerprint;
            document.getElementById('device_name').value = deviceName;
        })();

        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function () {
                var input = document.getElementById(button.getAttribute('data-target'));
                var icon = button.querySelector('i');
                var isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        });
    </script>
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
