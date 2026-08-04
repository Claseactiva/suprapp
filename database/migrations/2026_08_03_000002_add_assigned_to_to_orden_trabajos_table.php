<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedToToOrdenTrabajosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->unsignedInteger('assigned_to')->nullable()->after('user_id');

            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_trabajos', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn('assigned_to');
        });
    }
}
