<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTallerWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taller_workers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->unique();
            $table->unsignedInteger('taller_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('taller_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('taller_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taller_workers');
    }
}
