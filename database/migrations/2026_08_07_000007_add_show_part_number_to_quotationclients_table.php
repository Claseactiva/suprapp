<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowPartNumberToQuotationclientsTable extends Migration
{
    public function up()
    {
        Schema::table('quotationclients', function (Blueprint $table) {
            $table->boolean('show_part_number')->default(false)->after('spare_parts');
        });
    }

    public function down()
    {
        Schema::table('quotationclients', function (Blueprint $table) {
            $table->dropColumn('show_part_number');
        });
    }
}
