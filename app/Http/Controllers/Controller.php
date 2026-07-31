<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Resuelve la ruta absoluta de un archivo de imagen guardado en la BD,
     * soportando tanto la convencion antigua ('app/public/...', physicamente
     * en storage/app/public) como la nueva ('storage/...', physicamente en
     * public/storage, sin depender de un symlink).
     */
    protected function resolveImagePath($relativePath)
    {
        $relativePath = (string) $relativePath;

        if (str_starts_with($relativePath, 'app/public/')) {
            return storage_path($relativePath);
        }

        return public_path(ltrim($relativePath, '/'));
    }
}
