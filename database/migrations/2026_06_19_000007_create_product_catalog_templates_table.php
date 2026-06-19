<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCatalogTemplatesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('product_catalog_templates')) {
            Schema::create('product_catalog_templates', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('categoria', 191);
                $table->string('nombre', 191);
                $table->timestamps();

                $table->unique(['categoria', 'nombre'], 'pct_categoria_nombre_unique');
                $table->index('categoria');
                $table->index('nombre');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_catalog_templates');
    }
}
