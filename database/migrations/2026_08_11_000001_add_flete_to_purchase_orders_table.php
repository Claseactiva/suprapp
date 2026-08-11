<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFleteToPurchaseOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->decimal('flete', 12, 4)->default(0)->after('sin_iva');
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('flete');
        });
    }
}
