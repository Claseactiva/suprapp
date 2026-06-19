<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVehicleModelsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('product_vehicle_models')) {
            Schema::create('product_vehicle_models', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('vehicle_model_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();

                $table->unique(['product_id', 'vehicle_model_id'], 'pvm_product_vehicle_unique');
                $table->index(['user_id', 'vehicle_model_id'], 'pvm_user_vehicle_idx');
                $table->index('product_id');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_vehicle_models');
    }
}
