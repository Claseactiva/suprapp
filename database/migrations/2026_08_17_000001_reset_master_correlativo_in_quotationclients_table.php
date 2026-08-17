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
     * no choque con basura vieja.
     *
     * Se deja sembrado el correlativo 5990 en la ultima cotizacion de la
     * cuenta (la de mayor id) para que la numeracion nueva continue desde
     * ahi (la siguiente cotizacion que cree la cuenta 1 sera la N° 5991)
     * en vez de reiniciar desde 1.
     *
     * @return void
     */
    public function up()
    {
        DB::table('quotationclients')
            ->where('user_id', 1)
            ->update(['correlativo' => null, 'correlativo_suffix' => null]);

        $lastId = DB::table('quotationclients')->where('user_id', 1)->max('id');

        if ($lastId !== null) {
            DB::table('quotationclients')
                ->where('id', $lastId)
                ->update(['correlativo' => 5990]);
        }
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
