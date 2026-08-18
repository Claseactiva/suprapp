<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BackfillWorkerCorrelativoInQuotationclientsTable extends Migration
{
    /**
     * QuotationUserController@storeMechanic ("Cotizar Repuestos") no
     * asignaba correlativo antes de este fix, asi que las cotizaciones
     * que un vendedor creo con esa pantalla antes del deploy quedaron
     * con correlativo NULL (mostrando su id de tabla como fallback).
     *
     * Les asigna ahora el numero que les hubiera correspondido: el
     * ultimo correlativo del dueno del taller, con sufijo .1/.2/...
     * (mismo criterio que nextCorrelativo(), en orden de creacion).
     *
     * @return void
     */
    public function up()
    {
        $pendingRows = DB::table('quotationclients as q')
            ->join('taller_workers as tw', 'tw.user_id', '=', 'q.user_id')
            ->whereNull('q.correlativo')
            ->whereNull('q.correlativo_suffix')
            ->orderBy('q.created_at')
            ->select('q.id', 'tw.taller_id')
            ->get();

        foreach ($pendingRows as $row) {
            $base = (int) (DB::table('quotationclients')
                ->where('user_id', $row->taller_id)
                ->whereNull('correlativo_suffix')
                ->whereNotNull('correlativo')
                ->orderByDesc('created_at')
                ->value('correlativo') ?? 0);

            $lastSuffix = DB::table('quotationclients')
                ->where('correlativo', $base)
                ->whereNotNull('correlativo_suffix')
                ->max('correlativo_suffix');

            $suffix = $lastSuffix === null ? 1 : ((int) $lastSuffix) + 1;

            DB::table('quotationclients')
                ->where('id', $row->id)
                ->update(['correlativo' => $base, 'correlativo_suffix' => $suffix]);
        }
    }

    /**
     * No reversible: no habia nada util antes (correlativo era NULL).
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
