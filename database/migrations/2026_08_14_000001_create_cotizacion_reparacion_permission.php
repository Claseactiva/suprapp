<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class CreateCotizacionReparacionPermission extends Migration
{
    /**
     * Permiso separado de "cotizaciones" para poder mostrar/ocultar
     * "Cotización Reparación" en el sidebar y proteger su ruta de forma
     * independiente de "Cotizaciones" (formal). Antes ambas usaban el mismo
     * permiso 'cotizaciones' y no se podian dar por separado.
     *
     * Se otorga automaticamente a todos los roles que ya tenian
     * 'cotizaciones', para no dejar a nadie afuera de golpe (incluido el
     * dueno de la cuenta) ahora que la ruta queda protegida con
     * middleware. Ajustar por rol despues desde la pantalla de Roles.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::firstOrCreate(
            ['name' => 'cotizacion_reparacion', 'guard_name' => 'web'],
            ['description' => 'Cotización Reparación']
        );

        $cotizacionesId = DB::table('permissions')->where('name', 'cotizaciones')->value('id');

        if (!$cotizacionesId) {
            return;
        }

        $roleIds = DB::table('role_has_permissions')->where('permission_id', $cotizacionesId)->pluck('role_id');

        foreach ($roleIds as $roleId) {
            DB::table('role_has_permissions')->insertOrIgnore([
                'permission_id' => $permission->id,
                'role_id' => $roleId,
            ]);

            $role = DB::table('roles')->where('id', $roleId)->first();
            $legacyPermissions = json_decode($role->permissions ?? '[]', true) ?: [];

            if (!in_array($permission->id, $legacyPermissions)) {
                $legacyPermissions[] = $permission->id;
                DB::table('roles')->where('id', $roleId)->update([
                    'permissions' => json_encode(array_values($legacyPermissions)),
                ]);
            }
        }
    }

    /**
     * @return void
     */
    public function down()
    {
        $permission = DB::table('permissions')->where('name', 'cotizacion_reparacion')->first();

        if ($permission) {
            DB::table('role_has_permissions')->where('permission_id', $permission->id)->delete();
        }

        Permission::where('name', 'cotizacion_reparacion')->where('guard_name', 'web')->delete();
    }
}
