<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('delivery_times')) {
            Schema::create('delivery_times', function (Blueprint $table) {
                $table->id();
                $table->string('label')->unique();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });

            DB::table('delivery_times')->insert([
                'label' => '24 a 48 Hrs',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('delivery_times');
    }
};
