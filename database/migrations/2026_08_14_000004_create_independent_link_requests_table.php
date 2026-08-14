<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndependentLinkRequestsTable extends Migration
{
    /**
     * Vinculo explicito admin <-> cuenta independiente para poder compartir
     * cotizaciones puntuales. Sin una fila 'accepted' aqui, una cuenta
     * independiente no puede compartir nada (ver QuotationclientController@share).
     *
     * @return void
     */
    public function up()
    {
        Schema::create('independent_link_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('admin_id');
            $table->unsignedInteger('owner_user_id');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->unique(['admin_id', 'owner_user_id']);
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('owner_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('independent_link_requests');
    }
}
