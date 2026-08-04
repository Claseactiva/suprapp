<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRangeColumnsToVehicleEnginesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('vehicle_engines')) {
            return;
        }

        Schema::table('vehicle_engines', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicle_engines', 'vehicle_model_id')) {
                $table->unsignedInteger('vehicle_model_id')->nullable()->after('id');
                $table->index('vehicle_model_id');
            }
            if (!Schema::hasColumn('vehicle_engines', 'motor_spec_id')) {
                $table->unsignedInteger('motor_spec_id')->nullable()->after('vehicle_model_id');
                $table->index('motor_spec_id');
            }
            if (!Schema::hasColumn('vehicle_engines', 'year_from')) {
                $table->unsignedSmallInteger('year_from')->nullable()->after('motor_spec_id');
            }
            if (!Schema::hasColumn('vehicle_engines', 'year_to')) {
                $table->unsignedSmallInteger('year_to')->nullable()->after('year_from');
                $table->index(['vehicle_model_id', 'motor_spec_id', 'year_from', 'year_to'], 'vehicle_engines_range_lookup_idx');
            }
        });

        // year_id/v_engine son NOT NULL sin default; las filas nuevas basadas en rango
        // no las llenan. doctrine/dbal no esta instalado, asi que se usa ALTER crudo
        // en vez de ->change().
        DB::statement('ALTER TABLE vehicle_engines MODIFY year_id INT UNSIGNED NULL');
        DB::statement('ALTER TABLE vehicle_engines MODIFY v_engine VARCHAR(90) NULL');
    }

    public function down()
    {
        if (!Schema::hasTable('vehicle_engines')) {
            return;
        }

        DB::statement('ALTER TABLE vehicle_engines MODIFY year_id INT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE vehicle_engines MODIFY v_engine VARCHAR(90) NOT NULL');

        Schema::table('vehicle_engines', function (Blueprint $table) {
            if (Schema::hasColumn('vehicle_engines', 'year_to')) {
                $table->dropIndex('vehicle_engines_range_lookup_idx');
                $table->dropColumn('year_to');
            }
            if (Schema::hasColumn('vehicle_engines', 'year_from')) {
                $table->dropColumn('year_from');
            }
            if (Schema::hasColumn('vehicle_engines', 'motor_spec_id')) {
                $table->dropIndex(['motor_spec_id']);
                $table->dropColumn('motor_spec_id');
            }
            if (Schema::hasColumn('vehicle_engines', 'vehicle_model_id')) {
                $table->dropIndex(['vehicle_model_id']);
                $table->dropColumn('vehicle_model_id');
            }
        });
    }
}
