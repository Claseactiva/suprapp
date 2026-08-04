<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('motors')) {
            Schema::create('motors', function (Blueprint $table) {
                $table->id();
                $table->string('motor_number', 100)->nullable();
                $table->string('arreglo_cpl', 100)->nullable();
                $table->timestamps();

                $table->index('motor_number');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('motors');
    }
}
