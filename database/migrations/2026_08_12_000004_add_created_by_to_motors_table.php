<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToMotorsTable extends Migration
{
    public function up()
    {
        Schema::table('motors', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable()->after('id');
        });

        // Backfill: para motores con una asignacion vigente, se asume que el
        // dueno del vehiculo asignado es quien lo creo. Los motores sueltos
        // (sin asignacion historica) quedan con created_by null.
        DB::statement(
            'UPDATE motors
             INNER JOIN motor_assignments ON motor_assignments.motor_id = motors.id AND motor_assignments.fecha_fin IS NULL
             INNER JOIN vehicles ON vehicles.id = motor_assignments.vehicle_id
             SET motors.created_by = vehicles.user_id
             WHERE motors.created_by IS NULL'
        );
    }

    public function down()
    {
        Schema::table('motors', function (Blueprint $table) {
            $table->dropColumn('created_by');
        });
    }
}
