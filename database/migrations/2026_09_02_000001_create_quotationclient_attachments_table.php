<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationclientAttachmentsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('quotationclient_attachments')) {
            Schema::create('quotationclient_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('quotationclient_id');
                $table->string('path', 190);
                $table->string('original_name', 190);
                $table->string('mime_type', 120)->nullable();
                $table->unsignedBigInteger('size')->nullable();
                $table->timestamps();

                $table->foreign('quotationclient_id')->references('id')->on('quotationclients')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('quotationclient_attachments');
    }
}
