<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroInternoToMotorsTable extends Migration
{
    public function up()
    {
        Schema::table('motors', function (Blueprint $table) {
            $table->string('numero_interno', 100)->nullable()->after('motor_number');
        });
    }

    public function down()
    {
        Schema::table('motors', function (Blueprint $table) {
            $table->dropColumn('numero_interno');
        });
    }
}
