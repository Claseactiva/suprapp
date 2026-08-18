<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class LinkJairoWorkerAndFixCorrelativo extends Migration
{
    /**
     * Jairo Tapia (jairotapiaguzman@gmail.com) cotiza como vendedor del
     * admin (user_id 1), pero se creo con el rol "Sealer" por la pantalla
     * normal de "Nuevo Usuario", no por "vincular trabajador" -- nunca
     * quedo en taller_workers, asi que sus cotizaciones no recibian el
     * sufijo .1/.2 del correlativo del dueno.
     *
     * @return void
     */
    public function up()
    {
        $jairo = DB::table('users')->where('email', 'jairotapiaguzman@gmail.com')->first();

        if (!$jairo) {
            return;
        }

        DB::table('taller_workers')->insertOrIgnore([
            'user_id' => $jairo->id,
            'taller_id' => 1,
        ]);

        $pendingRows = DB::table('quotationclients')
            ->where('user_id', $jairo->id)
            ->whereNull('correlativo')
            ->whereNull('correlativo_suffix')
            ->orderBy('created_at')
            ->pluck('id');

        foreach ($pendingRows as $id) {
            $base = (int) (DB::table('quotationclients')
                ->where('user_id', 1)
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
                ->where('id', $id)
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
