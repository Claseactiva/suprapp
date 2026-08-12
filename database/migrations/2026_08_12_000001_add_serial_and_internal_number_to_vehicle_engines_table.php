<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSerialAndInternalNumberToVehicleEnginesTable extends Migration
{
    public function up()
    {
        Schema::table('vehicle_engines', function (Blueprint $table) {
            $table->string('serial_number')->nullable()->after('year_to');
            $table->string('numero_interno')->nullable()->after('serial_number');
        });
    }

    public function down()
    {
        Schema::table('vehicle_engines', function (Blueprint $table) {
            $table->dropColumn(['serial_number', 'numero_interno']);
        });
    }
}
