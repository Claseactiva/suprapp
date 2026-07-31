<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateVehicleQuantityOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_quantity_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('value')->unique();
            $table->timestamps();
        });

        $defaults = [1, 5, 10, 30, 50, 100, 500, 1000];
        $now = now();

        DB::table('vehicle_quantity_options')->insert(
            array_map(function ($value) use ($now) {
                return ['value' => $value, 'created_at' => $now, 'updated_at' => $now];
            }, $defaults)
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_quantity_options');
    }
}
