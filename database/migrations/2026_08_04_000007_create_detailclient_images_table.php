<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailclientImagesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('detailclient_images')) {
            Schema::create('detailclient_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('detailclient_id');
                $table->string('imagen', 190);
                $table->timestamps();

                $table->foreign('detailclient_id')->references('id')->on('detailclients')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('detailclient_images');
    }
}
