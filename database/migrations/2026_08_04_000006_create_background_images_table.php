<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackgroundImagesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('background_images')) {
            Schema::create('background_images', function (Blueprint $table) {
                $table->id();
                $table->string('path', 190);
                $table->boolean('is_light')->default(true);
                $table->unsignedInteger('uploaded_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('background_images');
    }
}
