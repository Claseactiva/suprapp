<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairActivitiesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('repair_activities')) {
            Schema::create('repair_activities', function (Blueprint $table) {
                $table->id();
                $table->string('name', 190);
                $table->decimal('price', 10, 0)->nullable();
                $table->timestamps();

                $table->unique('name');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('repair_activities');
    }
}
