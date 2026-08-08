<?php

/*
|--------------------------------------------------------------------------
| mbstring fallback
|--------------------------------------------------------------------------
|
| El PHP de produccion no siempre trae la extension mbstring habilitada.
| league/commonmark (usado por los correos en Markdown) necesita
| mb_strcut, que el polyfill de symfony/polyfill-mbstring no cubre.
| Este shim solo corta bytes y ajusta el borde para no partir un
| caracter UTF-8 a la mitad.
|
*/

if (! function_exists('mb_strcut')) {
    function mb_strcut($string, $start, $length = null, $encoding = null)
    {
        $substr = $length === null
            ? substr($string, $start)
            : substr($string, $start, $length);

        while ($substr !== '' && preg_match('//u', $substr) !== 1) {
            $substr = substr($substr, 0, -1);
        }

        return $substr;
    }
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
