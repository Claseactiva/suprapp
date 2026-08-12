<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroInternoAndMotorModelColumns extends Migration
{
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('numero_interno')->nullable()->after('tipo');
        });

        Schema::table('motors', function (Blueprint $table) {
            $table->string('modelo_motor')->nullable()->after('motor_number');
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('numero_interno');
        });

        Schema::table('motors', function (Blueprint $table) {
            $table->dropColumn('modelo_motor');
        });
    }
}
