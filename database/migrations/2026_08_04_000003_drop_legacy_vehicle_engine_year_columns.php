<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropLegacyVehicleEngineYearColumns extends Migration
{
    /**
     * Etapa C (irreversible por diseno): borra las filas legacy de
     * vehicle_engines (una por anio, ya migradas a rangos en la Etapa B),
     * las columnas year_id/v_engine, y la tabla vehicle_years completa.
     * Requiere que la Etapa B ya se haya corrido y verificado.
     */
    public function up()
    {
        if (Schema::hasTable('vehicle_engines')) {
            DB::table('vehicle_engines')->whereNull('year_from')->delete();

            if (Schema::hasColumn('vehicle_engines', 'year_id')) {
                DB::statement('ALTER TABLE vehicle_engines DROP FOREIGN KEY years_y_engine');
            }

            Schema::table('vehicle_engines', function (Blueprint $table) {
                if (Schema::hasColumn('vehicle_engines', 'year_id')) {
                    $table->dropIndex('year_id_index');
                    $table->dropColumn('year_id');
                }
                if (Schema::hasColumn('vehicle_engines', 'v_engine')) {
                    $table->dropColumn('v_engine');
                }
            });
        }

        Schema::dropIfExists('vehicle_years');
    }

    /**
     * Irreversible por diseno: una vez borrada vehicle_years y las filas
     * legacy, la recuperacion es desde el backup tomado antes de la Etapa B
     * (storage/app/backups/vehicle_engines_years_backup_20260804.sql).
     */
    public function down()
    {
        //
    }
}
