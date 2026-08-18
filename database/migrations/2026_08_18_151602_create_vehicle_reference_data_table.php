<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleReferenceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_reference_data', function (Blueprint $table) {
            $table->id();
            $table->string('periodo', 7);
            $table->string('cod_prt', 30)->nullable();
            $table->string('ppu', 20)->nullable();
            $table->string('ppu_norm', 20);
            $table->string('cod_vehiculo', 10)->nullable();
            $table->string('cod_combustible', 10)->nullable();
            $table->string('cod_servicio', 10)->nullable();
            $table->string('marca', 100)->nullable();
            $table->string('modelo', 150)->nullable();
            $table->string('ano_fabricacion', 4)->nullable();
            $table->string('num_motor', 100)->nullable();
            $table->string('num_chasis', 100)->nullable();

            $table->index('ppu_norm');
            $table->index('periodo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_reference_data');
    }
}
