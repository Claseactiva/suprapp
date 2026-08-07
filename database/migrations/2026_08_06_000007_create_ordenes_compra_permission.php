<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

class CreateOrdenesCompraPermission extends Migration
{
    /**
     * Solo crea el permiso; la asignacion a roles se hace manualmente
     * desde la pantalla de administracion de roles ya existente.
     *
     * @return void
     */
    public function up()
    {
        Permission::firstOrCreate([
            'name' => 'ordenes_compra',
            'guard_name' => 'web',
        ]);
    }

    /**
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'ordenes_compra')->where('guard_name', 'web')->delete();
    }
}
