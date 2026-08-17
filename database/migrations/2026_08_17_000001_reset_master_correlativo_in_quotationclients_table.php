<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ResetMasterCorrelativoInQuotationclientsTable extends Migration
{
    /**
     * La cuenta 1 (master) calculaba su correlativo persiguiendo MAX(id)
     * en cada creacion en vez de su propio ultimo numero, asi que quedo
     * con valores repetidos/inconsistentes en miles de filas historicas.
     * No se reordenan esas cotizaciones viejas (siguen mostrando su id,
     * como siempre); solo se limpia el campo para que la numeracion
     * nueva (ver QuotationclientController::latestOwnerCorrelativoBase)
     * arranque en 1 sin chocar con basura vieja.
     *
     * @return void
     */
    public function up()
    {
        DB::table('quotationclients')
            ->where('user_id', 1)
            ->update(['correlativo' => null, 'correlativo_suffix' => null]);
    }

    /**
     * No reversible: los valores viejos de correlativo eran basura
     * (duplicados/inconsistentes), no hay nada util a lo que volver.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
