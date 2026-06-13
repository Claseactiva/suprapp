<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotationclients', function (Blueprint $table) {
            $table->string('internal_number')->nullable()->after('ppu');
            $table->string('chasis')->nullable()->after('internal_number');
            $table->string('motor_number')->nullable()->after('chasis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotationclients', function (Blueprint $table) {
            $table->dropColumn(['internal_number', 'chasis', 'motor_number']);
        });
    }
};
