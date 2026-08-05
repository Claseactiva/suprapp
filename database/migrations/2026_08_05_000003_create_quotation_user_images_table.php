<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationUserImagesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('quotation_user_images')) {
            Schema::create('quotation_user_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('quotation_user_vehicle_id');
                $table->string('imagen', 190);
                $table->timestamps();

                $table->foreign('quotation_user_vehicle_id')->references('id')->on('quotation_user_vehicles')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('quotation_user_images');
    }
}
