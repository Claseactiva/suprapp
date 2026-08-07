<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUserIdToDeliveryTimesTable extends Migration
{
    public function up()
    {
        Schema::table('delivery_times', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        DB::table('delivery_times')->whereNull('user_id')->update(['user_id' => 1]);
    }

    public function down()
    {
        Schema::table('delivery_times', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
