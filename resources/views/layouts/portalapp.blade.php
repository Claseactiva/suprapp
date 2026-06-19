<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SupraApp') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">


    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app-principal.css') }}" rel="stylesheet">
    <script src="https://code.iconify.design/1/1.0.6/iconify.min.js"></script>

</head>

<body id="page-top" class="sidebar-toggled admin-compact">

    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

        <a class="navbar-brand mr-1" href="{{ route('home') }}">SupraApp</a>

        <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Navbar -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('perfil') }}">
                        <span class="iconify" data-icon="mdi:account" data-inline="false"></span> {{ __('Mi Perfil') }}
                    </a>

                    <a class="dropdown-item" href="#"
                        onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                        <span class="iconify" data-icon="mdi:logout" data-inline="false"></span>
                        {{ __('Cerrar Sesión') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout', ['url' => Auth::user()->url]) }}"
                        method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

    </nav>

    <div id="wrapper">

        <ul class="sidebar navbar-nav toggled">

            @can('clientes')
                <li id="clientes" class="nav-item {{ request()->routeIs('admin-clientes') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-clientes') }}">
                        <i class="fas fa-users-cog"></i>
                        <span>Empresas / Proveedores</span></a>
                </li>
            @endcan

            @can('productos')
                <li id="productos" class="nav-item {{ request()->routeIs('admin-productos') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-productos') }}">
                        <i class="fas fa-dolly-flatbed"></i>
                        <span>Productos</span></a>
                </li>
            @endcan

            @can('stocks')
                <li id="inventario" class="nav-item {{ request()->routeIs('admin-inventario') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-inventario') }}">
                        <i class="fas fa-dolly-flatbed"></i>
                        <span>Inventario</span></a>
                </li>
            @endcan

            @can('lista-precios')
                <li id="lista-precios" class="nav-item {{ request()->routeIs('admin-lista-precios') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-lista-precios') }}">
                        <i class="fas fa-dolly-flatbed"></i>
                        <span>Lista de precios</span></a>
                </li>
            @endcan

            @can('utilidades')
                <li id="utilidad" class="nav-item {{ request()->routeIs('admin-utilidad') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-utilidad') }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Formas de Pagos</span></a>
                </li>
            @endcan

            @can('vehiculos')
                <li id="vehiculos" class="nav-item {{ request()->routeIs('admin-vehiculos') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-vehiculos') }}">
                        <i class="fas fa-car"></i>
                        <span>Registro Vehículos</span></a>
                </li>
            @endcan

            @can('ordenes_trabajo')
                <li id="orden-trabajos" class="nav-item {{ request()->routeIs('admin-orden-trabajos') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-orden-trabajos') }}">
                        <i class="fas fa-wrench"></i>
                        <span>Ordenes de Trabajos</span></a>
                </li>
            @endcan

            @can('check-list')
                <li id="orden-trabajos" class="nav-item {{ request()->routeIs('admin-check-list') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-check-list') }}">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Check List</span></a>
                </li>
            @endcan

            @can('vehiculos_mecanicos')
                <li id="vehiculos-m" class="nav-item {{ request()->routeIs('admin-vehiculosM') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-vehiculosM') }}">
                        <i class="fas fa-car"></i>
                        <span>Registro Vehículos (Mecánico)</span></a>
                </li>
            @endcan

            @can('marcas')
                <li id="marcas" class="nav-item {{ request()->routeIs('admin-marca-vehiculos') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-marca-vehiculos') }}">
                        <i class="fas fa-car"></i>
                        <span>Marcas y Modelos de Vehículos</span></a>
                </li>
            @endcan

            @can('notas')
                <li id="notas" class="nav-item {{ request()->routeIs('admin-notas') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-notas') }}">
                        <i class="fas fa-file"></i>
                        <span>Notas</span></a>
                </li>
            @endcan

            @can('ventas')
                <li id="ventas" class="nav-item {{ request()->routeIs('admin-ventas') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-ventas') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Ventas</span>
                    </a>
                </li>
            @endcan

            @can('cotizaciones_simples')
                <li id="cotizaciones"
                    class="nav-item {{ request()->routeIs('admin-cotizacion-express') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-cotizacion-express') }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Cotizaciones Express</span></a>
                </li>
            @endcan

            @can('cotizaciones')
                <li id="cotizaciones"
                    class="nav-item {{ request()->routeIs('admin-cotizaciones-formales') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-cotizaciones-formales') }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Cotizaciones</span></a>
                </li>
            @endcan

            @can('envios')
                <li id="envios" class="nav-item {{ request()->routeIs('admin-envios') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-envios') }}">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Envios</span></a>
                </li>
            @endcan



            @can('boletas')
                <li id="bills" class="nav-item {{ request()->routeIs('admin-boleta') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-boleta') }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Boletas</span></a>
                </li>
            @endcan

            @can('importaciones')
                <li id="imports" class="nav-item {{ request()->routeIs('admin-importaciones') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-importaciones') }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Importaciones</span></a>
                </li>
            @endcan

            @can('usuarios')
                <li id="users" class="nav-item {{ request()->routeIs('admin-usuarios') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-usuarios') }}">
                        <i class="fas fa-users"></i>
                        <span>Usuarios</span></a>
                </li>
            @endcan

            @can('usuarios_mecanicos')
                <li id="mechanic-users" class="nav-item {{ request()->routeIs('admin-usuariosM') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-usuariosM') }}">
                        <i class="fas fa-users"></i>
                        <span>Usuarios (mecánico)</span></a>
                </li>
            @endcan

            @can('roles')
                <li id="roles" class="nav-item {{ request()->routeIs('admin-roles') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin-roles') }}">
                        <i class="fas fa-cogs"></i>
                        <span>Roles de Usuario</span></a>
                </li>
            @endcan


        </ul>


        <div id="content-wrapper">
            <div id="container-fluid">
                @yield('content')
            </div>
        </div>

    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/app-principal.js') }}"></script>
</body>

</html>
