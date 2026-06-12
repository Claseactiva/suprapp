<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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
            'email' => [
                'required', 'email', 'min:6', 'max:150',
                \Illuminate\Validation\Rule::unique('users')->ignore(User::find($id))
            ],
            'password' => 'required|min:6|max:190',
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min' => 'El campo nombre debe tener al menos 6 caracteres',
            'name.max' => 'El campo nombre debe tener a lo más 190 caracteres',
            'email.required' => 'El campo correo electrónico es obligatorio',
            'email.min' => 'El campo de correo electrónico debe tener al menos 6 caracteres',
            'email.max' => 'El campo de correo electrónico debe tener a lo más 150 caracteres',
            'password.required' => 'El campo contraseña es obligatorio',
            'password.min' => 'El campo de contraseña debe tener al menos 6 caracteres',
            'password.max' => 'El campo de contraseña debe tener a lo más 150 caracteres',
        ]);

        $data = $request->all();

        $data['password'] = bcrypt($data['password']);
        $data['url'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
        $data['cant_vehicle'] = 5;
        $data['cant_client'] = 5;

        DB::table('quotationclients')->where('id', $id)->update(
            [
                'generado_client' => 1,
            ]
        );

        return $id;
    }

    public function show()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

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
            'password' => 'required|min:6|max:190',
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min' => 'El campo nombre debe tener al menos 6 caracteres',
            'name.max' => 'El campo nombre debe tener a lo más 190 caracteres',
            'email.required' => 'El campo correo electrónico es obligatorio',
            'email.min' => 'El campo de correo electrónico debe tener al menos 6 caracteres',
            'email.max' => 'El campo de correo electrónico debe tener a lo más 150 caracteres',
            'password.required' => 'El campo contraseña es obligatorio',
            'password.min' => 'El campo de contraseña debe tener al menos 6 caracteres',
            'password.max' => 'El campo de contraseña debe tener a lo más 150 caracteres',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['url'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);

        User::find($id)->update($data);

        return;
    }

    public function destroy($id)
    {
        $mechanics = DB::table('mechanic_client')
            ->join('users', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.user_id', '=', $id)->delete();

        if ($mechanics) {
            $user = User::findOrFail($id);
            $user->delete();
        } else {
            $user = User::findOrFail($id);
            $user->delete();
        }


        return;
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function clients()
    {
        $user_id = Auth::id();

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
        $user = $this->store($request);

        $user->roles()->sync(array(0 => '3'));

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
    }

    public function storeclient2(Request $request)
    {
        $id = Auth::id();


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
            return response()->json('¡Supero la cantidad de clientes!', 422);
        } else {
            if ($total_vehicles >= $users[0]->cant_vehicle) {
                return response()->json('¡Supero la cantidad de vehiculos!', 422);
            } else {
                $data = $request->all();

                $data['password'] = bcrypt($data['password']);
                $data['url'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
                $data['cant_vehicle'] = 5;

                $user = User::create($data);
                $user->roles()->sync(array(0 => '3'));

                DB::table('mechanic_client')->insertOrIgnore(
                    [
                        'user_id' => $user->id,
                        'mechanic_id' => Auth::id()
                    ]
                );
            }
        }
    }

    public function updateRole(Request $request, User $user)
    {
        $user->roles()->sync($request->all());
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
            return response()->json('¡La cantidad no puede ser 0!', 422);
        } else {
            if ($total > $mechanics[0]->cant_vehicle) {
                return response()->json('¡Error, Ya no puede crear mas vehiculos!', 422);
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

}
