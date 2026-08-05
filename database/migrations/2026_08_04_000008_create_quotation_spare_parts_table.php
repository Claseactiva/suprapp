<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationSparePartsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('quotation_spare_parts')) {
            Schema::create('quotation_spare_parts', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('quotationclient_id');
                $table->unsignedInteger('product_id')->nullable();
                $table->string('product', 190);
                $table->string('detail', 190)->nullable();
                $table->integer('quantity')->default(1);
                $table->timestamps();

                $table->foreign('quotationclient_id')->references('id')->on('quotationclients')->onDelete('cascade');
                $table->index('product_id');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('quotation_spare_parts');
    }
}
