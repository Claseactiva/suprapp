<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserAndProductIdToVehicleModelProductsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vehicle_model_products')) {
            Schema::table('vehicle_model_products', function (Blueprint $table) {
                if (!Schema::hasColumn('vehicle_model_products', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('vehicle_model_id');
                    $table->index('user_id');
                }

                if (!Schema::hasColumn('vehicle_model_products', 'product_id')) {
                    $table->unsignedBigInteger('product_id')->nullable()->after('detailclient_id');
                    $table->index('product_id');
                    $table->index(['user_id', 'vehicle_model_id', 'product_id'], 'vmp_user_model_product_idx');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('vehicle_model_products')) {
            Schema::table('vehicle_model_products', function (Blueprint $table) {
                if (Schema::hasColumn('vehicle_model_products', 'product_id')) {
                    $table->dropIndex('vmp_user_model_product_idx');
                    $table->dropIndex(['product_id']);
                    $table->dropColumn('product_id');
                }

                if (Schema::hasColumn('vehicle_model_products', 'user_id')) {
                    $table->dropIndex(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }
    }
}
