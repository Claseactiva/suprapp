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

<body id="page-top" class="sidebar-toggled admin-compact bg-photo-light">

    <script>
        if (localStorage.getItem('theme-light-tables') === '1') {
            document.body.classList.add('theme-light-tables');
        }
    </script>

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

                    <form id="logout-form" action="{{ route('logout') }}"
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

            @canany(['vehiculos', 'vehiculos_mecanicos', 'ordenes_trabajo', 'check-list', 'marcas'])
                @php
                    $vehiculosGroupActive = request()->routeIs('admin-vehiculos', 'admin-vehiculosM', 'admin-orden-trabajos', 'admin-check-list', 'admin-marca-vehiculos', 'admin-motores');
                @endphp
                <li class="nav-item">
                    <a class="nav-link sidebar-group-toggle {{ $vehiculosGroupActive ? '' : 'collapsed' }}" href="#"
                        data-toggle="collapse" data-target="#sidebarGroupVehiculos" aria-expanded="{{ $vehiculosGroupActive ? 'true' : 'false' }}">
                        <i class="fas fa-car"></i>
                        <span>Vehículos</span>
                        <i class="fas fa-chevron-down ml-auto sidebar-group-caret"></i>
                    </a>
                    <ul id="sidebarGroupVehiculos" class="collapse list-unstyled sidebar-group-menu {{ $vehiculosGroupActive ? 'show' : '' }}">
                        @can('vehiculos')
                            <li class="nav-item {{ request()->routeIs('admin-vehiculos') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-vehiculos') }}">
                                    <span>Registro Vehículos</span></a>
                            </li>
                        @endcan
                        @can('vehiculos_mecanicos')
                            <li class="nav-item {{ request()->routeIs('admin-vehiculosM') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-vehiculosM') }}">
                                    <span>Registro Vehículos (Mecánico)</span></a>
                            </li>
                        @endcan
                        @can('ordenes_trabajo')
                            <li class="nav-item {{ request()->routeIs('admin-orden-trabajos') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-orden-trabajos') }}">
                                    <span>Ordenes de Trabajos</span></a>
                            </li>
                        @endcan
                        @can('check-list')
                            <li class="nav-item {{ request()->routeIs('admin-check-list') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-check-list') }}">
                                    <span>Check List</span></a>
                            </li>
                        @endcan
                        @can('marcas')
                            <li class="nav-item {{ request()->routeIs('admin-marca-vehiculos') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-marca-vehiculos') }}">
                                    <span>Marcas y Modelos de Vehículos</span></a>
                            </li>
                        @endcan
                        @can('vehiculos')
                            <li class="nav-item {{ request()->routeIs('admin-motores') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-motores') }}">
                                    <span>Motores</span></a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['cotizaciones_simples', 'ventas', 'boletas'])
                @php
                    $comercialGroupActive = request()->routeIs('admin-cotizacion-express', 'admin-ventas', 'admin-boleta');
                @endphp
                <li class="nav-item">
                    <a class="nav-link sidebar-group-toggle {{ $comercialGroupActive ? '' : 'collapsed' }}" href="#"
                        data-toggle="collapse" data-target="#sidebarGroupComercial" aria-expanded="{{ $comercialGroupActive ? 'true' : 'false' }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Comercial</span>
                        <i class="fas fa-chevron-down ml-auto sidebar-group-caret"></i>
                    </a>
                    <ul id="sidebarGroupComercial" class="collapse list-unstyled sidebar-group-menu {{ $comercialGroupActive ? 'show' : '' }}">
                        @can('cotizaciones_simples')
                            <li class="nav-item {{ request()->routeIs('admin-cotizacion-express') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-cotizacion-express') }}">
                                    <span>Cotizaciones Express</span></a>
                            </li>
                        @endcan
                        @can('ventas')
                            <li class="nav-item {{ request()->routeIs('admin-ventas') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-ventas') }}">
                                    <span>Ventas</span></a>
                            </li>
                        @endcan
                        @can('boletas')
                            <li class="nav-item {{ request()->routeIs('admin-boleta') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-boleta') }}">
                                    <span>Boletas</span></a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['productos', 'stocks', 'lista-precios', 'utilidades', 'importaciones'])
                @php
                    $catalogoGroupActive = request()->routeIs('admin-productos', 'admin-inventario', 'admin-lista-precios', 'admin-utilidad', 'admin-importaciones');
                @endphp
                <li class="nav-item">
                    <a class="nav-link sidebar-group-toggle {{ $catalogoGroupActive ? '' : 'collapsed' }}" href="#"
                        data-toggle="collapse" data-target="#sidebarGroupCatalogo" aria-expanded="{{ $catalogoGroupActive ? 'true' : 'false' }}">
                        <i class="fas fa-dolly-flatbed"></i>
                        <span>Catálogo</span>
                        <i class="fas fa-chevron-down ml-auto sidebar-group-caret"></i>
                    </a>
                    <ul id="sidebarGroupCatalogo" class="collapse list-unstyled sidebar-group-menu {{ $catalogoGroupActive ? 'show' : '' }}">
                        @can('productos')
                            <li class="nav-item {{ request()->routeIs('admin-productos') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-productos') }}">
                                    <span>Productos</span></a>
                            </li>
                        @endcan
                        @can('stocks')
                            <li class="nav-item {{ request()->routeIs('admin-inventario') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-inventario') }}">
                                    <span>Inventario</span></a>
                            </li>
                        @endcan
                        @can('lista-precios')
                            <li class="nav-item {{ request()->routeIs('admin-lista-precios') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-lista-precios') }}">
                                    <span>Lista de precios</span></a>
                            </li>
                        @endcan
                        @can('utilidades')
                            <li class="nav-item {{ request()->routeIs('admin-utilidad') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-utilidad') }}">
                                    <span>Formas de Pagos</span></a>
                            </li>
                        @endcan
                        @can('importaciones')
                            <li class="nav-item {{ request()->routeIs('admin-importaciones') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-importaciones') }}">
                                    <span>Importaciones</span></a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['usuarios', 'usuarios_mecanicos', 'roles', 'notas'])
                @php
                    $adminGroupActive = request()->routeIs('admin-usuarios', 'admin-usuariosM', 'admin-roles', 'admin-cantidad-vehiculos', 'admin-notas');
                @endphp
                <li class="nav-item">
                    <a class="nav-link sidebar-group-toggle {{ $adminGroupActive ? '' : 'collapsed' }}" href="#"
                        data-toggle="collapse" data-target="#sidebarGroupAdmin" aria-expanded="{{ $adminGroupActive ? 'true' : 'false' }}">
                        <i class="fas fa-cogs"></i>
                        <span>Administración</span>
                        <i class="fas fa-chevron-down ml-auto sidebar-group-caret"></i>
                    </a>
                    <ul id="sidebarGroupAdmin" class="collapse list-unstyled sidebar-group-menu {{ $adminGroupActive ? 'show' : '' }}">
                        @can('usuarios')
                            <li class="nav-item {{ request()->routeIs('admin-usuarios') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-usuarios') }}">
                                    <span>Usuarios</span></a>
                            </li>
                        @endcan
                        @can('usuarios_mecanicos')
                            <li class="nav-item {{ request()->routeIs('admin-usuariosM') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-usuariosM') }}">
                                    <span>Usuarios (mecánico)</span></a>
                            </li>
                        @endcan
                        @can('roles')
                            <li class="nav-item {{ request()->routeIs('admin-roles') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-roles') }}">
                                    <span>Roles de Usuario</span></a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('admin-cantidad-vehiculos') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-cantidad-vehiculos') }}">
                                    <span>Opciones de Cantidad</span></a>
                            </li>
                        @endcan
                        @can('notas')
                            <li class="nav-item {{ request()->routeIs('admin-notas') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin-notas') }}">
                                    <span>Notas</span></a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

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
