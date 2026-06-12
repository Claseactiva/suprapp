const mix = require('laravel-mix');
mix.disableNotifications();
mix.js(['resources/assets/js/sbAdmin/sb-admin.js'],'public/js/app-principal.js')
    .js(['resources/assets/js/app.js'], 'public/js')
    .vue() // Agrega esta línea para configurar el cargador de archivos Vue
    .styles([
        'resources/assets/css/bootstrap-4.1.1.css',
        'resources/assets/css/sbAdmin/sb-admin.css',
        'resources/assets/css/toastr.css',
        'resources/assets/css/custom.css',
        'node_modules/bootstrap4-fullscreen-modal/dist/bootstrap4-modal-fullscreen.css',
        'node_modules/axios-progress-bar/dist/nprogress.css',
    ], 'public/css/app-principal.css');
