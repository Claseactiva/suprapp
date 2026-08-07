<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Models\Vehicle;
use App\Notifications\SetInitialPasswordNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Guardado por createUserAndSendActivation() para el ultimo usuario
     * creado; los callers lo pegan a la respuesta recien al final, despues
     * de cualquier ->update()/->save() sobre el modelo (activation_url no
     * es una columna real, agregarlo antes rompe esos updates).
     */
    private $lastActivationUrl;

    public function index()
    {
        //$users = User::doesntHave('mechanic')->orderBy('id', 'DESC')->with('roles')->paginate((int) request('per_page', 20));

        $users = User::orderBy('id', 'DESC')->with('roles')->paginate((int) request('per_page', 20));

        return [
            'pagination' => [
                'total'         => $users->total(),
                'current_page'  => $users->currentPage(),
                'per_page'      => $users->perPage(),
                'last_page'     => $users->lastPage(),
                'from'          => $users->firstItem(),
                'to'            => $users->lastItem(),
            ],
            'users' => $users
        ];
    }

    public function store(Request $request)
    {
        $id = request('id');

        $this->validate($request, [
            'name' => 'required|min:4|max:190',
            'email' => 'required|email|min:6|max:150|unique:users,email',
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min' => 'El campo nombre debe tener al menos 6 caracteres',
            'name.max' => 'El campo nombre debe tener a lo más 190 caracteres',
            'email.required' => 'El campo correo electrónico es obligatorio',
            'email.min' => 'El campo de correo electrónico debe tener al menos 6 caracteres',
            'email.max' => 'El campo de correo electrónico debe tener a lo más 150 caracteres',
            'email.unique' => 'Ya existe un usuario con ese correo electrónico',
        ]);

        $user = $this->createUserAndSendActivation([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'is_independent' => $request->boolean('is_independent'),
        ]);

        if ($id) {
            $quotation = DB::table('quotationclients')->where('id', $id)->first();

            if ($quotation) {
                $user->roles()->sync(array(0 => '3'));
                $this->applyRoleDefaultQuantity($user);

                $quotationOwner = User::find($quotation->user_id);
                $mechanicId = $quotationOwner ? $quotationOwner->effectiveTallerId() : $quotation->user_id;

                DB::table('mechanic_client')->insertOrIgnore([
                    'user_id' => $user->id,
                    'mechanic_id' => $mechanicId,
                ]);
            }

            DB::table('quotationclients')->where('id', $id)->update([
                'generado_client' => 1,
            ]);
        }

        return $this->withActivationUrl($user);
    }

    /**
     * Crea el usuario con una contraseña provisoria inutilizable y le envia
     * un correo para que configure su propia contraseña (reutiliza el
     * mecanismo estandar de "olvide mi contraseña" de Laravel).
     */
    private function createUserAndSendActivation(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt(Str::random(40)),
            'cant_vehicle' => $data['cant_vehicle'] ?? 5,
            'cant_client' => $data['cant_client'] ?? 5,
            'is_independent' => $data['is_independent'] ?? false,
        ]);

        $token = Password::createToken($user);
        $user->notify(new SetInitialPasswordNotification($token));

        // Se guarda el link ademas de mandarlo por correo, por si el cliente
        // no tiene correo o no le llega: se puede copiar y mandar a mano
        // (WhatsApp, SMS, etc). Se pega a $user recien al final de cada
        // caller (no aqui) para que no quede como atributo "sucio" y rompa
        // un $user->update() posterior (activation_url no es una columna).
        $this->lastActivationUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        return $user;
    }

    private function withActivationUrl(User $user)
    {
        $user->activation_url = $this->lastActivationUrl;

        return $user;
    }

    public function show()
    {
        $user_id = Auth::id();
        $user = User::with('roles')->find($user_id);
        $user->cotizar_id = $user->effectiveTallerId();

        return $user;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:4|max:190',
            'email' => [
                'required', 'email', 'min:6', 'max:150',
                \Illuminate\Validation\Rule::unique('users')->ignore(User::find($id))
            ],
            'password' => 'nullable|min:6|max:190',
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min' => 'El campo nombre debe tener al menos 6 caracteres',
            'name.max' => 'El campo nombre debe tener a lo más 190 caracteres',
            'email.required' => 'El campo correo electrónico es obligatorio',
            'email.min' => 'El campo de correo electrónico debe tener al menos 6 caracteres',
            'email.max' => 'El campo de correo electrónico debe tener a lo más 150 caracteres',
            'password.min' => 'El campo de contraseña debe tener al menos 6 caracteres',
            'password.max' => 'El campo de contraseña debe tener a lo más 150 caracteres',
        ]);

        $data = $request->all();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        User::find($id)->update($data);

        return;
    }

    /**
     * Le manda al usuario un correo para que defina una nueva contraseña,
     * reutilizando el mismo mecanismo de "olvide mi contraseña" que se usa
     * al crear la cuenta (ver createUserAndSendActivation).
     */
    public function sendPasswordReset($id)
    {
        $user = User::findOrFail($id);

        $token = Password::createToken($user);
        $user->notify(new SetInitialPasswordNotification($token));

        $activationUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        return response()->json([
            'message' => 'Correo enviado correctamente',
            'activation_url' => $activationUrl,
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ((int) Auth::id() === (int) $user->id) {
            return response()->json([
                'error' => 'No puedes eliminar el usuario con el que estas conectado.',
            ], 422);
        }

        $dependencies = $this->collectDeleteDependencies($user->id);

        if (!empty($dependencies)) {
            return response()->json([
                'error' => 'No se puede eliminar el usuario porque tiene datos asociados: ' . implode(', ', $dependencies) . '.',
            ], 422);
        }

        try {
            DB::transaction(function () use ($user) {
                DB::table('mechanic_client')->where('user_id', $user->id)->delete();
                DB::table('mechanic_client')->where('mechanic_id', $user->id)->delete();

                if (Schema::hasTable('model_has_roles')) {
                    DB::table('model_has_roles')
                        ->where('model_id', $user->id)
                        ->where('model_type', User::class)
                        ->delete();
                }

                if (Schema::hasTable('model_has_permissions')) {
                    DB::table('model_has_permissions')
                        ->where('model_id', $user->id)
                        ->where('model_type', User::class)
                        ->delete();
                }

                $user->delete();
            });
        } catch (\Throwable $exception) {
            return response()->json([
                'error' => 'No se pudo eliminar el usuario. Revisa si aun tiene registros asociados.',
            ], 422);
        }

        return response()->json([
            'message' => 'Usuario eliminado con exito',
        ]);
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * Conteos agregados de un usuario (pensado para cuentas "independientes"):
     * el admin puede ver cuanta actividad tiene, sin poder abrir el detalle.
     */
    public function metrics($id)
    {
        abort_unless((int) Auth::id() === 1, 403);

        $user = User::findOrFail($id);

        $clientsByType = \App\Models\Client::where('user_id', $user->id)
            ->where('type', '<>', 'Cliente Particular')
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        return response()->json([
            'clients_total' => (int) $clientsByType->sum(),
            'clients_by_type' => $clientsByType,
            'quotations' => \App\Models\Quotationclient::where('user_id', $user->id)->count(),
            'purchase_orders' => \App\Models\PurchaseOrder::where('user_id', $user->id)->count(),
            'products' => \App\Models\Product::withUserClients($user->id)->count(),
            'envios' => \App\Models\QuotationShipping::where('user_id', $user->id)->count(),
            'tipos_pagos' => \App\Models\TipoPago::where('user_id', $user->id)->count(),
        ]);
    }

    public function clients()
    {
        $user_id = Auth::user()->effectiveTallerId();

        $clients = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.mechanic_id', '=', $user_id)
            ->select('users.id', 'users.name', 'users.email', 'users.password', 'users.url', 'users.cant_vehicle')->get();
        return $clients;
    }

    public function sumavehi()
    {
        $user_id = Auth::id();

        $suma_vehicles = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.mechanic_id', '=', $user_id)
            ->sum('users.cant_vehicle');

        return $suma_vehicles;
    }

    public function storeclient(Request $request)
    {
        $id = request('id');

        $this->validate($request, [
            'name' => 'required|min:4|max:190',
            'email' => 'required|email|min:6|max:150|unique:users,email',
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'email.required' => 'El campo correo electrónico es obligatorio',
            'email.unique' => 'Ya existe un usuario con ese correo electrónico',
        ]);

        $user = $this->createUserAndSendActivation([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        // Rol "Quote": cliente final del mecanico, ve su vehiculo y le agrega
        // Detalle, pero no puede crear vehiculos por su cuenta.
        $user->roles()->sync(array(0 => '15'));
        $this->applyRoleDefaultQuantity($user);

        DB::table('mechanic_client')->insertOrIgnore(
            [
                'user_id' => $user->id,
                'mechanic_id' => Auth::id()
            ]
        );

        DB::table('quotationclients')->where('id', $id)->update(
            [
                'generado_client' => 1,
            ]
        );

        return $this->withActivationUrl($user);
    }

    public function storeclient2(Request $request)
    {
        $id = Auth::user()->effectiveTallerId();


        $total_clients = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.mechanic_id', '=', $id)
            ->count();

        $users = DB::table('users')->where('id', '=', $id)->get();

        $clients = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.mechanic_id', '=', $id)
            ->select('users.id', 'users.name', 'users.email', 'users.password', 'users.created_at', 'users.updated_at')
            ->get();

        $client_ids = array();

        foreach ($clients as $client) {
            array_push($client_ids, $client->id);
        }


        $total_vehicles = Vehicle::with('user')->whereIn('user_id', $client_ids)->count();


        if ($total_clients >= $users[0]->cant_vehicle) {
            return response()->json('Supero la cantidad de clientes!', 422);
        } else {
            if ($total_vehicles >= $users[0]->cant_vehicle) {
                return response()->json('Supero la cantidad de vehiculos!', 422);
            } else {
                $this->validate($request, [
                    'name' => 'required|min:4|max:190',
                    'email' => 'required|email|min:6|max:150|unique:users,email',
                ], [
                    'name.required' => 'El campo nombre es obligatorio',
                    'email.required' => 'El campo correo electrónico es obligatorio',
                    'email.unique' => 'Ya existe un usuario con ese correo electrónico',
                ]);

                $user = $this->createUserAndSendActivation([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                ]);
                // Rol "Quote": cliente final del mecanico, ve su vehiculo y le agrega
                // Detalle, pero no puede crear vehiculos por su cuenta.
                $user->roles()->sync(array(0 => '15'));
                $this->applyRoleDefaultQuantity($user);

                DB::table('mechanic_client')->insertOrIgnore(
                    [
                        'user_id' => $user->id,
                        'mechanic_id' => $id
                    ]
                );

                return $this->withActivationUrl($user);
            }
        }
    }

    public function team()
    {
        $userIds = Auth::user()->teamUserIds();

        $team = User::whereIn('id', $userIds)->select('id', 'name')->get();

        return $team;
    }

    public function workers()
    {
        $tallerId = Auth::user()->effectiveTallerId();

        $workers = DB::table('users')
            ->join('taller_workers', 'users.id', '=', 'taller_workers.user_id')
            ->where('taller_workers.taller_id', '=', $tallerId)
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->get();

        return $workers;
    }

    public function storeWorker(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4|max:190',
            'email' => 'required|email|min:6|max:150|unique:users,email',
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'email.required' => 'El campo correo electrónico es obligatorio',
            'email.unique' => 'Ya existe un usuario con ese correo electrónico',
        ]);

        $tallerId = Auth::user()->effectiveTallerId();

        $user = $this->createUserAndSendActivation([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        $user->roles()->sync(array(0 => '14'));

        DB::table('taller_workers')->insertOrIgnore(
            [
                'user_id' => $user->id,
                'taller_id' => $tallerId,
            ]
        );

        return $this->withActivationUrl($user);
    }

    public function revokeWorker($id)
    {
        $tallerId = Auth::user()->effectiveTallerId();

        $link = DB::table('taller_workers')
            ->where('user_id', $id)
            ->where('taller_id', $tallerId)
            ->first();

        if (!$link) {
            return response()->json([
                'error' => 'El trabajador no pertenece a tu taller.',
            ], 422);
        }

        DB::table('taller_workers')->where('user_id', $id)->delete();

        if (Schema::hasTable('model_has_roles')) {
            DB::table('model_has_roles')
                ->where('model_id', $id)
                ->where('model_type', User::class)
                ->delete();
        }

        return response()->json([
            'message' => 'Acceso revocado con exito',
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $user->roles()->sync($request->all());
        $this->applyRoleDefaultQuantity($user);
    }

    /**
     * Si el rol asignado tiene una cantidad de vehiculos por defecto
     * configurada, se la aplica al usuario. El admin puede seguir
     * sobreescribiendola despues por usuario individual.
     */
    private function applyRoleDefaultQuantity(User $user)
    {
        $role = $user->roles()->first();

        if ($role && $role->default_cant_vehicle !== null) {
            $user->update(['cant_vehicle' => $role->default_cant_vehicle]);
        }
    }

    public function updateCantVehicleUser(Request $request, $id)
    {
        $mechanic = Auth::id();
        $data = $request->all();


        $suma_vehicles = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.mechanic_id', '=', $mechanic)
            ->sum('users.cant_vehicle');

        $mechanics = DB::table('users')->where('id', '=', $mechanic)->get();

        $total = $data['cant_vehicle'] + $suma_vehicles;

        if ($data['cant_vehicle'] == 0) {
            return response()->json('La cantidad no puede ser 0!', 422);
        } else {
            if ($total > $mechanics[0]->cant_vehicle) {
                return response()->json('Error, ya no puede crear mas vehiculos!', 422);
            } else {

                DB::table('users')->where('id', $id)->update(
                    [
                        'cant_vehicle' => $data['cant_vehicle']
                    ]
                );
                return;
            }
        }
    }

    public function updateCantCliVehiUser(Request $request, $id)
    {
        $data = $request->all();

        DB::table('users')->where('id', $id)->update(
            [
                'cant_vehicle' => $data['cant_vehicle']
            ]
        );

        return;
    }

    public function quotation_roles()
    {
        $user_id = Auth::id();
        $users = User::where('id', '=', $user_id)->with('roles')->get();

        return $users;
    }


    public function totalVehi($id)
    {
        $vehicles = DB::table('vehicles')->where('user_id', '=', $id)->count();

        return $vehicles;
    }


    public function totalCli()
    {
        $mechanic = Auth::id();
        $suma_clients = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.mechanic_id', '=', $mechanic)
            ->count();

        $users = DB::table('users')->where('id', '=', $mechanic)->get();

        $total_clients = ['total_clients' => ($users[0]->cant_client - $suma_clients)];
        return $total_clients;
    }



    public function totalCliAdmin($id)
    {
        $suma_clients = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.mechanic_id', '=', $id)
            ->count();

        $users = DB::table('users')->where('id', '=', $id)->get();

        $total_clients = ['total_clients' => ($users[0]->cant_client - $suma_clients)];
        return $total_clients;
    }


    public function totalVehiAdmin($id)
    {
        $suma_vehicles = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.mechanic_id', '=', $id)
            ->sum('users.cant_vehicle');
        $users = DB::table('users')->where('id', '=', $id)->get();

        $total_vehicles   = ['total_vehicles' => ($users[0]->cant_vehicle - $suma_vehicles)];
        return $total_vehicles;
    }

    private function collectDeleteDependencies($userId)
    {
        $dependencies = [];

        // Consulta todas las FK que apuntan a users.id en esta BD
        $foreignKeys = DB::select("
            SELECT TABLE_NAME, COLUMN_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND REFERENCED_TABLE_NAME = 'users'
              AND REFERENCED_COLUMN_NAME = 'id'
        ");

        // Tablas que se limpian dentro de la transacción — no bloquean
        $ignoredTables = ['mechanic_client', 'model_has_roles', 'model_has_permissions'];

        foreach ($foreignKeys as $fk) {
            if (in_array($fk->TABLE_NAME, $ignoredTables)) {
                continue;
            }

            $count = DB::table($fk->TABLE_NAME)->where($fk->COLUMN_NAME, $userId)->count();

            if ($count > 0) {
                $dependencies[] = $count . ' registros en ' . $fk->TABLE_NAME;
            }
        }

        // Dependencias indirectas a través de los clientes del usuario
        if (Schema::hasTable('clients')) {
            $clientIds = DB::table('clients')->where('user_id', $userId)->pluck('id');

            if ($clientIds->isNotEmpty()) {
                $clientForeignKeys = DB::select("
                    SELECT TABLE_NAME, COLUMN_NAME
                    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND REFERENCED_TABLE_NAME = 'clients'
                      AND REFERENCED_COLUMN_NAME = 'id'
                ");

                foreach ($clientForeignKeys as $fk) {
                    $count = DB::table($fk->TABLE_NAME)->whereIn($fk->COLUMN_NAME, $clientIds)->count();

                    if ($count > 0) {
                        $dependencies[] = $count . ' registros en ' . $fk->TABLE_NAME . ' (via clientes)';
                    }
                }
            }
        }

        return $dependencies;
    }
}

