<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientMechanicsTable extends Migration
{
    /**
     * Vincula un usuario "mecanico" con un Client (empresa) para que pueda
     * ver/operar la flota de vehiculos de esa empresa como si fuera propia.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_mechanics', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('mechanic_id');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('mechanic_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['client_id', 'mechanic_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_mechanics');
    }
}
