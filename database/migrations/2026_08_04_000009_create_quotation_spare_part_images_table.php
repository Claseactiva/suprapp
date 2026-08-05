<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationSparePartImagesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('quotation_spare_part_images')) {
            Schema::create('quotation_spare_part_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('quotation_spare_part_id');
                $table->string('imagen', 190);
                $table->timestamps();

                $table->foreign('quotation_spare_part_id')->references('id')->on('quotation_spare_parts')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('quotation_spare_part_images');
    }
}
