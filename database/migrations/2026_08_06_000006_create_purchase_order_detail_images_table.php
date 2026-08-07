<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderDetailImagesTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_order_detail_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_detail_id');
            $table->string('imagen', 190);
            $table->timestamps();

            $table->foreign('purchase_order_detail_id')->references('id')->on('purchase_order_details')->onDelete('cascade');
            $table->index('purchase_order_detail_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_order_detail_images');
    }
}
