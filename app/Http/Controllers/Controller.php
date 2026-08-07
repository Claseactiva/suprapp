<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Corta el acceso (403) a un registro que no pertenece al usuario logueado,
     * salvo que sea el admin (id 1) viendo el registro de una cuenta que NO esta
     * marcada como independiente. Se usa en show/update/destroy/pdf de modulos
     * donde el listado ya filtra por dueno pero el acceso directo por ID no.
     */
    protected function authorizeOwner($ownerUserId)
    {
        $currentUserId = (int) Auth::id();
        $ownerUserId = (int) $ownerUserId;

        if ($currentUserId === $ownerUserId) {
            return;
        }

        if ($currentUserId === 1) {
            $ownerIsIndependent = User::where('id', $ownerUserId)->value('is_independent');

            if (!$ownerIsIndependent) {
                return;
            }
        }

        abort(403);
    }

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
