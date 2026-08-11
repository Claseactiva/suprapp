<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class IncreaseDecimalPrecisionInPurchaseOrderDetailsTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE purchase_order_details MODIFY price DECIMAL(12,4) UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE purchase_order_details MODIFY quantity DECIMAL(12,4) UNSIGNED NOT NULL DEFAULT 1');
        DB::statement('ALTER TABLE purchase_order_details MODIFY total DECIMAL(12,4) UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        DB::statement('ALTER TABLE purchase_order_details MODIFY price DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE purchase_order_details MODIFY quantity DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 1');
        DB::statement('ALTER TABLE purchase_order_details MODIFY total DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0');
    }
}
