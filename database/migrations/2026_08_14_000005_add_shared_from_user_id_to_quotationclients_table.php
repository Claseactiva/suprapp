<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSharedFromUserIdToQuotationclientsTable extends Migration
{
    /**
     * Marca una cotizacion como copia de una cotizacion compartida por una
     * cuenta independiente (ver QuotationclientController@share), para poder
     * mostrar "Compartida por: X" en el listado.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotationclients', function (Blueprint $table) {
            $table->unsignedInteger('shared_from_user_id')->nullable()->after('correlativo_suffix');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('quotationclients', function (Blueprint $table) {
            $table->dropColumn('shared_from_user_id');
        });
    }
}
