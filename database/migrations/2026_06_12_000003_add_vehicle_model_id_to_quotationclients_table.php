<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVehicleModelIdToQuotationclientsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('quotationclients', 'vehicle_model_id')) {
            Schema::table('quotationclients', function (Blueprint $table) {
                $table->unsignedBigInteger('vehicle_model_id')->nullable()->after('vehicle');
                $table->index('vehicle_model_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('quotationclients', 'vehicle_model_id')) {
            Schema::table('quotationclients', function (Blueprint $table) {
                $table->dropIndex(['vehicle_model_id']);
                $table->dropColumn('vehicle_model_id');
            });
        }
    }
}
