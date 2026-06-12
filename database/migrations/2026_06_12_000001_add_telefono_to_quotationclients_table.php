<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('quotationclients', 'telefono')) {
            Schema::table('quotationclients', function (Blueprint $table) {
                $table->text('telefono')->nullable()->after('url');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('quotationclients', 'telefono')) {
            Schema::table('quotationclients', function (Blueprint $table) {
                $table->dropColumn('telefono');
            });
        }
    }
};
