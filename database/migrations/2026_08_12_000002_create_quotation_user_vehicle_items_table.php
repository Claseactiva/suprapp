<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationUserVehicleItemsTable extends Migration
{
    public function up()
    {
        Schema::create('quotation_user_vehicle_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('quotation_user_vehicle_id');
            $table->string('description');
            $table->unsignedInteger('qty')->default(1);
            $table->timestamps();

            $table->foreign('quotation_user_vehicle_id')
                ->references('id')->on('quotation_user_vehicles')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotation_user_vehicle_items');
    }
}
