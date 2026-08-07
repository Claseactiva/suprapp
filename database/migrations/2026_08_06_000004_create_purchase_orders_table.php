<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * "Proveedor" reusa la tabla clients (Empresas/Proveedores), igual que
     * quotationclients.client_id, en vez de tener una tabla propia.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->integer('correlativo')->nullable();
            $table->text('payment')->nullable();
            $table->text('supplier_text')->nullable();
            $table->text('telefono')->nullable();
            $table->text('url')->nullable();
            $table->text('state')->nullable();
            $table->integer('generado')->default(2);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('clients')->onDelete('set null');
            $table->index('user_id');
            $table->index('supplier_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
