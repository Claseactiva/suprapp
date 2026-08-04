<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorAssignmentsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('motor_assignments')) {
            Schema::create('motor_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('motor_id');
                $table->unsignedInteger('vehicle_id');
                $table->timestamp('fecha_inicio')->useCurrent();
                $table->timestamp('fecha_fin')->nullable();
                $table->timestamps();

                $table->foreign('motor_id')->references('id')->on('motors')->onDelete('cascade');
                $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
                $table->index(['motor_id', 'fecha_fin']);
                $table->index(['vehicle_id', 'fecha_fin']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('motor_assignments');
    }
}
