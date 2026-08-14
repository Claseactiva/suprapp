<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class CreateMotoresPermission extends Migration
{
    /**
     * Permiso separado de "vehiculos" para poder mostrar/ocultar "Motores"
     * (registro real de motores, admin-motores) en el sidebar y proteger su
     * ruta de forma independiente del resto del grupo Vehiculos (Registro
     * Vehiculos, Ordenes de Trabajo, Check List, etc).
     *
     * Se otorga automaticamente a todos los roles que ya tenian 'vehiculos',
     * para no dejar a nadie afuera de golpe ahora que la ruta queda protegida
     * con middleware. Ajustar por rol despues desde la pantalla de Roles.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::firstOrCreate(
            ['name' => 'motores', 'guard_name' => 'web'],
            ['description' => 'Motores']
        );

        $vehiculosId = DB::table('permissions')->where('name', 'vehiculos')->value('id');

        if (!$vehiculosId) {
            return;
        }

        $roleIds = DB::table('role_has_permissions')->where('permission_id', $vehiculosId)->pluck('role_id');

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
        $permission = DB::table('permissions')->where('name', 'motores')->first();

        if ($permission) {
            DB::table('role_has_permissions')->where('permission_id', $permission->id)->delete();
        }

        Permission::where('name', 'motores')->where('guard_name', 'web')->delete();
    }
}
