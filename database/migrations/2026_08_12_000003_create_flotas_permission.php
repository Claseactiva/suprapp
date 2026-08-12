<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

class CreateFlotasPermission extends Migration
{
    /**
     * Permiso separado de "vehiculos" para dar acceso solo a "Flota por
     * Cliente" (por ejemplo a un mecanico vinculado a una empresa) sin
     * destrabar Registro Vehiculos / Ordenes de Trabajo / Check List.
     * La asignacion a roles se hace manualmente desde la pantalla de
     * administracion de roles ya existente.
     *
     * @return void
     */
    public function up()
    {
        Permission::firstOrCreate(
            ['name' => 'flotas', 'guard_name' => 'web'],
            ['description' => 'Flota por Cliente']
        );
    }

    /**
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'flotas')->where('guard_name', 'web')->delete();
    }
}
