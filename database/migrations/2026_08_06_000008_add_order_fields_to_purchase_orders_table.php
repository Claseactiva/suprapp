<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderFieldsToPurchaseOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('order_number', 190)->nullable()->after('correlativo');
            $table->string('buyer', 190)->nullable()->after('order_number');
            $table->string('currency', 60)->nullable()->after('buyer');
            $table->date('promised_date')->nullable()->after('currency');
            $table->string('shipping_method', 190)->nullable()->after('promised_date');
            $table->string('payment_terms', 190)->nullable()->after('payment');
            $table->string('requested_by', 190)->nullable()->after('payment_terms');
            $table->text('ship_to')->nullable()->after('requested_by');
        });
    }

    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['order_number', 'buyer', 'currency', 'promised_date', 'shipping_method', 'payment_terms', 'requested_by', 'ship_to']);
        });
    }
}
