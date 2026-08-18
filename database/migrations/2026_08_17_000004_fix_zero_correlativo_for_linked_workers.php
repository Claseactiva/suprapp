<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixZeroCorrelativoForLinkedWorkers extends Migration
{
    /**
     * Las migraciones anteriores (2026_08_17_000002 y _000003) solo
     * buscaban correlativo NULL, pero en produccion la fila de Jairo
     * (y potencialmente otras) quedo con correlativo = 0 (valor por
     * defecto de la columna), no NULL, asi que nunca se corrigio.
     *
     * Repite el mismo backfill pero tratando 0 como "sin asignar",
     * para cualquier trabajador vinculado en taller_workers (no solo
     * Jairo), por si hay mas casos iguales.
     *
     * @return void
     */
    public function up()
    {
        $pendingRows = DB::table('quotationclients as q')
            ->join('taller_workers as tw', 'tw.user_id', '=', 'q.user_id')
            ->where(function ($query) {
                $query->whereNull('q.correlativo')->orWhere('q.correlativo', 0);
            })
            ->whereNull('q.correlativo_suffix')
            ->orderBy('q.created_at')
            ->select('q.id', 'tw.taller_id')
            ->get();

        foreach ($pendingRows as $row) {
            $base = (int) (DB::table('quotationclients')
                ->where('user_id', $row->taller_id)
                ->whereNull('correlativo_suffix')
                ->whereNotNull('correlativo')
                ->where('correlativo', '>', 0)
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
     * @return void
     */
    public function down()
    {
        //
    }
}
