<?php

namespace App\Traits;

use App\Models\Quotationclient;
use App\User;

trait AssignsQuotationCorrelativo
{
    /**
     * Calcula el correlativo (y sufijo) de una nueva cotizacion segun quien la crea.
     * El dueno del taller avanza el numero base normalmente. Un vendedor
     * (sub-usuario "Workshop Personal") NO avanza el numero base: toma el
     * ultimo del dueno y le agrega un sufijo .1, .2, ... que se reinicia
     * cada vez que el dueno genera una cotizacion nueva.
     *
     * @return array{correlativo: int, correlativo_suffix: int|null}
     */
    protected function nextCorrelativo($userId)
    {
        $user = User::find($userId);
        $ownerId = $user ? $user->effectiveTallerId() : (int) $userId;
        $isOwner = ((int) $userId === (int) $ownerId);

        $base = $this->latestOwnerCorrelativoBase($ownerId);

        if ($isOwner) {
            return ['correlativo' => $base + 1, 'correlativo_suffix' => null];
        }

        $lastSuffix = Quotationclient::whereIn('user_id', $user->teamUserIds())
            ->where('correlativo', $base)
            ->whereNotNull('correlativo_suffix')
            ->max('correlativo_suffix');

        return ['correlativo' => $base, 'correlativo_suffix' => $lastSuffix === null ? 1 : ((int) $lastSuffix) + 1];
    }

    /**
     * Ultimo numero mostrado para las cotizaciones creadas por el propio
     * dueno del taller (no por sus vendedores). Cotizaciones antiguas sin
     * correlativo asignado (legacy, mostraban el id de la fila) no cuentan
     * como base: la primera cotizacion nueva arranca en 1.
     */
    protected function latestOwnerCorrelativoBase($ownerId)
    {
        $quotationclient = Quotationclient::where('user_id', '=', $ownerId)
            ->whereNull('correlativo_suffix')
            ->whereNotNull('correlativo')
            ->where('correlativo', '>', 0)
            ->select('correlativo')
            ->latest()
            ->first();

        return $quotationclient === null ? 0 : (int) $quotationclient->correlativo;
    }
}
