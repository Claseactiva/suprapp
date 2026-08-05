<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoToQuotationclientsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('quotationclients', 'tipo')) {
            Schema::table('quotationclients', function (Blueprint $table) {
                $table->string('tipo', 20)->default('repuesto')->after('id');
                $table->index('tipo');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('quotationclients', 'tipo')) {
            Schema::table('quotationclients', function (Blueprint $table) {
                $table->dropColumn('tipo');
            });
        }
    }
}
