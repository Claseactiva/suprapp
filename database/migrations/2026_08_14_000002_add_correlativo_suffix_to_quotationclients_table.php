<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCorrelativoSuffixToQuotationclientsTable extends Migration
{
    /**
     * Sufijo (.1, .2, ...) para cotizaciones creadas por un vendedor
     * (sub-usuario "Workshop Personal" de un taller). El numero base
     * (correlativo) queda igual al ultimo del dueno del taller, sin
     * avanzarlo, y este sufijo distingue cada cotizacion del vendedor
     * dentro de ese mismo numero base.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotationclients', function (Blueprint $table) {
            $table->unsignedInteger('correlativo_suffix')->nullable()->after('correlativo');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('quotationclients', function (Blueprint $table) {
            $table->dropColumn('correlativo_suffix');
        });
    }
}
