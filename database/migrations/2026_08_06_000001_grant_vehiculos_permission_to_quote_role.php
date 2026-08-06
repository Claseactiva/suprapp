<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class GrantVehiculosPermissionToQuoteRole extends Migration
{
    /**
     * El rol "Quote" (Cliente cotizacion, id 15) se usa para los clientes finales
     * que crea un mecanico/taller: pueden ver su propio vehiculo y agregarle
     * Detalle, pero no crear vehiculos nuevos (eso se controla en el codigo,
     * no por permiso). Sin el permiso "vehiculos" no verian ni el modulo.
     *
     * @return void
     */
    public function up()
    {
        $role = DB::table('roles')->where('id', 15)->first();

        if (!$role) {
            return;
        }

        $permissions = json_decode($role->permissions ?? '[]', true) ?: [];

        if (!in_array(5, $permissions)) {
            $permissions[] = 5;
            DB::table('roles')->where('id', 15)->update([
                'permissions' => json_encode(array_values($permissions)),
            ]);
        }

        DB::table('role_has_permissions')->insertOrIgnore([
            'permission_id' => 5,
            'role_id' => 15,
        ]);
    }

    /**
     * @return void
     */
    public function down()
    {
        DB::table('role_has_permissions')
            ->where('role_id', 15)
            ->where('permission_id', 5)
            ->delete();

        $role = DB::table('roles')->where('id', 15)->first();

        if ($role) {
            $permissions = json_decode($role->permissions ?? '[]', true) ?: [];
            $permissions = array_values(array_diff($permissions, [5]));

            DB::table('roles')->where('id', 15)->update([
                'permissions' => json_encode($permissions),
            ]);
        }
    }
}
