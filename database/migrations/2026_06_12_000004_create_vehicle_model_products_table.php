<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleModelProductsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('vehicle_model_products')) {
            Schema::create('vehicle_model_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vehicle_model_id');
                $table->unsignedBigInteger('quotationclient_id')->nullable();
                $table->unsignedBigInteger('detailclient_id')->unique();
                $table->string('product_name');
                $table->string('product_key');
                $table->string('product_code')->nullable();
                $table->string('source', 20)->default('live');
                $table->timestamps();

                $table->index('vehicle_model_id');
                $table->index('quotationclient_id');
                $table->index(['vehicle_model_id', 'product_key']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_model_products');
    }
}
