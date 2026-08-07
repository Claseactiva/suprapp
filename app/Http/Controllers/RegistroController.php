<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as PasswordRule;

class RegistroController extends Controller
{
    /**
     * Formulario publico de autorregistro: se comparte un link por cuenta
     * (mismo id que /cotizar/{id}), sin necesitar de antemano el correo
     * del cliente. El propio cliente completa nombre, correo y contrasena.
     */
    public function show($id = null)
    {
        return view('registro', ['ownerId' => $id, 'quotationId' => null]);
    }

    /**
     * Variante del link de autorregistro atada a una cotizacion puntual
     * (no a la cuenta en general), para poder identificar despues a que
     * cliente corresponde cada cuenta creada. Se usa cuando el link se
     * comparte desde el boton "Crear Usuario" de una cotizacion especifica.
     */
    public function showFromQuotation($quotationId)
    {
        return view('registro', ['ownerId' => null, 'quotationId' => $quotationId]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:4|max:190',
            'email' => 'required|email|min:6|max:150|unique:users,email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'email.required' => 'El campo correo electronico es obligatorio',
            'email.email' => 'El correo electronico no es valido',
            'email.unique' => 'Ya existe un usuario con ese correo electronico',
            'password.required' => 'El campo contraseña es obligatorio',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        $quotationId = $request->input('quotation_id');
        $quotation = $quotationId ? DB::table('quotationclients')->where('id', $quotationId)->first() : null;

        if ($quotation) {
            $ownerId = (int) $quotation->user_id;
        } else {
            $ownerId = (int) $request->input('owner_id');
        }
        if ($ownerId <= 0) {
            $ownerId = 1;
        }

        $owner = User::find($ownerId);
        $mechanicId = $owner ? (int) $owner->effectiveTallerId() : 1;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'cant_vehicle' => 5,
        ]);

        // Rol "Cliente" si se registra por el link de la cuenta admin,
        // rol "Quote" (mas restringido) si se registra por el link de un
        // mecanico/taller, igual que cuando el mecanico lo crea a mano.
        $roleId = $mechanicId === 1 ? 3 : 15;
        $user->roles()->sync([$roleId]);

        $role = $user->roles()->first();
        if ($role && $role->default_cant_vehicle !== null) {
            $user->update(['cant_vehicle' => $role->default_cant_vehicle]);
        }

        DB::table('mechanic_client')->insertOrIgnore([
            'user_id' => $user->id,
            'mechanic_id' => $mechanicId,
        ]);

        if ($quotation) {
            DB::table('quotationclients')->where('id', $quotation->id)->update([
                'generado_client' => 1,
            ]);
        }

        return redirect()->route('login')->with('status', 'Cuenta creada correctamente. Ya puedes iniciar sesion.');
    }
}
