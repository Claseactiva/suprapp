<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorSpecsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('motor_specs')) {
            Schema::create('motor_specs', function (Blueprint $table) {
                $table->increments('id');
                $table->decimal('cilindrada', 4, 2)->nullable();
                $table->string('combustible', 30)->nullable();
                $table->string('raw_label', 90);
                $table->timestamps();

                $table->unique(['cilindrada', 'combustible'], 'motor_specs_cilindrada_combustible_unique');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('motor_specs');
    }
}
