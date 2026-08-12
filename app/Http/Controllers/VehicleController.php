<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Vehicle;
use App\Models\DetailVehicle;
use App\Models\Motor;
use App\Models\MotorAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();

        $users = User::where('id', '=', $user_id)->with('roles')->get();

        foreach ($users as $user) {
            foreach ($user->roles as $roles) {
                if ($roles->name == 'admin') {

                    $ownerScope = request('owner_scope');

                    $vehicles = Vehicle::with('user', 'currentMotor.motor')
                        ->whereNotIn('user_id', User::where('is_independent', true)->pluck('id'))
                        ->when($ownerScope === 'mine', function ($query) use ($user_id) {
                            return $query->where('user_id', $user_id);
                        })
                        ->when($ownerScope === 'workshops', function ($query) use ($user_id) {
                            return $query->where('user_id', '<>', $user_id);
                        })
                        ->patent()
                        ->name()
                        ->year()
                        ->client()
                        ->paginate((int) request('per_page', 20));

                    return [
                        'pagination' => [
                            'total'         => $vehicles->total(),
                            'current_page'  => $vehicles->currentPage(),
                            'per_page'      => $vehicles->perPage(),
                            'last_page'     => $vehicles->lastPage(),
                            'from'          => $vehicles->firstItem(),
                            'to'            => $vehicles->lastItem(),
                        ],
                        'vehicles' => $vehicles
                    ];
                } else {
                    $vehicles = Vehicle::orderBy('id', 'DESC')
                        ->whereIn('user_id', $user->teamUserIds())
                        ->with('user', 'currentMotor.motor')
                        ->patent()
                        ->name()
                        ->year()
                        ->paginate((int) request('per_page', 20));

                    return [
                        'pagination' => [
                            'total'         => $vehicles->total(),
                            'current_page'  => $vehicles->currentPage(),
                            'per_page'      => $vehicles->perPage(),
                            'last_page'     => $vehicles->lastPage(),
                            'from'          => $vehicles->firstItem(),
                            'to'            => $vehicles->lastItem(),
                        ],
                        'vehicles' => $vehicles,
                        'rol' => $roles->name
                    ];
                }
            }
        }
    }

    public function clientvehicles()
    {
        $user_id =  Auth::user()->effectiveTallerId();

        $clients = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.mechanic_id', '=', $user_id)
            ->select('users.id', 'users.name', 'users.email', 'users.password', 'users.created_at', 'users.updated_at')
            ->get();

        $client_ids = array($user_id);

        foreach ($clients as $client) {
            array_push($client_ids, $client->id);
        }


        $vehicles = Vehicle::with('user')->whereIn('user_id', $client_ids)->get();

        return $vehicles;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->hasRole('Quote')) {
            return response()->json('No tienes permiso para crear vehiculos. Contacta a tu mecanico.', 403);
        }

        $id = Auth::user()->effectiveTallerId();

        $vehicles = DB::table('vehicles')->whereIn('user_id', Auth::user()->teamUserIds())->count();

        $users = DB::table('users')->where('id', '=', $id)->get();


        if ($vehicles >= $users[0]->cant_vehicle) {
            return response()->json('¡Error, Ya no puede crear mas vehiculos!', 422);
        } else {

            $data = $request->all();

            if ($data['user_id'] == '') {
                $data['user_id'] = Auth::id();
            }

            DB::transaction(function () use ($data, $request) {
                $vehicle = Vehicle::create($data);
                $this->attachMotorIfProvided($request, $vehicle);
            });
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMechanic(Request $request)
    {

        $id = Auth::id();

        $data = $request->all();

        if ($data['user_id'] == '') {
            $user_id = $data['user_id'] = Auth::id();
        } else {
            $user_id = $data['user_id'];
        }

        $vehicles = DB::table('vehicles')->where('user_id', '=', $user_id)->count();

        $users = DB::table('users')->where('id', '=', $user_id)->get();

        // Si no se eligio cliente, el vehiculo queda a nombre del propio mecanico
        // (se le puede asignar un cliente despues editandolo). En ese caso no
        // corresponde el doble chequeo de cupo contra un cliente registrado.
        if ($user_id == Auth::id()) {
            if ($vehicles >= $users[0]->cant_vehicle) {
                return response()->json('¡Error, Ya no puede crear mas vehiculos!', 422);
            }

            DB::transaction(function () use ($data, $request) {
                $vehicle = Vehicle::create($data);
                $this->attachMotorIfProvided($request, $vehicle);
            });

            return;
        }

        $mechanics = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.user_id', '=', $user_id)
            ->get();


        $clients = DB::table('users')
            ->join('mechanic_client', 'users.id', '=', 'mechanic_client.user_id')
            ->where('mechanic_client.mechanic_id', '=', $mechanics[0]->mechanic_id)
            ->select('users.id', 'users.name', 'users.email', 'users.password', 'users.created_at', 'users.updated_at')
            ->get();

        $client_ids = array();

        foreach ($clients as $client) {
            array_push($client_ids, $client->id);
        }

        $total_vehicles = Vehicle::with('user')->whereIn('user_id', $client_ids)->count();


        if ($vehicles >= $users[0]->cant_vehicle) {
            return response()->json('¡Error, Ya no puede crear mas vehiculos!', 422);
        } else {
            if ($total_vehicles >= $mechanics[0]->cant_vehicle) {
                return response()->json('¡Error, Ya no puede crear mas vehiculos!', 422);
            } else {

                $data = $request->all();

                if ($data['user_id'] == '') {
                    $data['user_id'] = Auth::id();
                }

                DB::transaction(function () use ($data, $request) {
                    $vehicle = Vehicle::create($data);
                    $this->attachMotorIfProvided($request, $vehicle);
                });
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $vehicle = Vehicle::findOrFail($id)->detail_vehicles;

        $vehicle = DetailVehicle::where('vehicle_id', '=', $id)->with('vehicle', 'images')->get();


        return $vehicle;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'patent' => 'required|min:4|max:190',
            'chasis' => 'required|min:4|max:190',
            'color' => 'required|min:4|max:190',
            'km' => 'nullable|required_without:horometro|max:190',
            'horometro' => 'nullable|required_without:km|max:190',

        ], [
            'patent.required' => 'El campo patente es obligatorio',
            'patent.min' => 'El campo patente debe tener al menos 4 caracteres',
            'patent.max' => 'El campo patente debe tener a lo más 190 caracteres',
            'chasis.required' => 'El campo chasis es obligatorio',
            'chasis.min' => 'El campo chasis debe tener al menos 4 caracteres',
            'chasis.max' => 'El campo chasis debe tener a lo más 190 caracteres',
            'color.required' => 'El campo color es obligatorio',
            'color.min' => 'El campo color debe tener al menos 4 caracteres',
            'color.max' => 'El campo color debe tener a lo más 190 caracteres',
            'km.required_without' => 'Debe indicar kilometraje u horómetro',
            'km.max' => 'El campo km debe tener a lo más 190 caracteres',
            'horometro.required_without' => 'Debe indicar kilometraje u horómetro',
            'horometro.max' => 'El campo horómetro debe tener a lo más 190 caracteres',
        ]);

        $vehicle = Vehicle::find($id);
        $vehicle->update($request->all());
        $this->syncMotor($request, $vehicle);

        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return response()->json(['message' => 'Vehiculo enviado a la papelera']);
    }

    /**
     * Lista los vehiculos eliminados (papelera), con el mismo alcance por rol que index().
     */
    public function trashed()
    {
        $user = Auth::user();

        $query = Vehicle::onlyTrashed()->with('user')->orderBy('deleted_at', 'DESC');

        if (!$user->hasRole('admin')) {
            $query->whereIn('user_id', $user->teamUserIds());
        }

        $vehicles = $query->paginate((int) request('per_page', 20));

        return [
            'pagination' => [
                'total'         => $vehicles->total(),
                'current_page'  => $vehicles->currentPage(),
                'per_page'      => $vehicles->perPage(),
                'last_page'     => $vehicles->lastPage(),
                'from'          => $vehicles->firstItem(),
                'to'            => $vehicles->lastItem(),
            ],
            'vehicles' => $vehicles
        ];
    }

    public function restore($id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        $vehicle->restore();

        return response()->json(['message' => 'Vehiculo restaurado correctamente']);
    }

    /**
     * Borrado definitivo desde la papelera: aqui si hay que limpiar check_list_vehicles
     * a mano porque es la unica FK que restringe el borrado real de vehicles (el resto
     * de tablas hijas se limpian solas por cascade).
     */
    public function forceDestroy($id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);

        DB::table('check_list_vehicles')->where('vehicle_id', $vehicle->id)->delete();

        $vehicle->forceDelete();

        return response()->json(['message' => 'Vehiculo eliminado definitivamente']);
    }

    public function indexByUser()
    {
        $user_id = Auth::id();
        $vehicles = Vehicle::orderBy('id', 'DESC')
            ->where('user_id', '=', $user_id)
            ->with('user', 'currentMotor.motor')
            ->paginate((int) request('per_page', 20));

        return [
            'pagination' => [
                'total'         => $vehicles->total(),
                'current_page'  => $vehicles->currentPage(),
                'per_page'      => $vehicles->perPage(),
                'last_page'     => $vehicles->lastPage(),
                'from'          => $vehicles->firstItem(),
                'to'            => $vehicles->lastItem(),
            ],
            'vehicles' => $vehicles
        ];
    }

    /**
     * Lista la flota de equipos ya asociada a un cliente (empresa).
     */
    public function byClient($clientId)
    {
        $vehicles = Vehicle::where('client_id', $clientId)
            ->with('user', 'currentMotor.motor')
            ->orderBy('id', 'DESC')
            ->get();

        return $vehicles;
    }

    /**
     * Crea varios equipos de una sola vez, todos asociados al mismo cliente (flota).
     */
    public function storeBulkForClient(Request $request, $clientId)
    {
        $this->validate($request, [
            'vehicles' => 'required|array|min:1',
            'vehicles.*.patent' => 'required|min:4|max:190',
            'vehicles.*.tipo' => 'nullable|max:190',
            'vehicles.*.numero_interno' => 'nullable|max:190',
            'vehicles.*.chasis' => 'nullable|max:190',
            'vehicles.*.brand' => 'nullable|max:190',
            'vehicles.*.model' => 'nullable|max:190',
            'vehicles.*.year' => 'nullable|max:190',
            'vehicles.*.color' => 'nullable|max:190',
            'vehicles.*.km' => 'nullable|max:190',
            'vehicles.*.horometro' => 'nullable|max:190',
            'vehicles.*.motor_number' => 'nullable|max:190',
            'vehicles.*.motor_model' => 'nullable|max:190',
            'vehicles.*.arreglo_cpl' => 'nullable|max:190',
        ]);

        $userId = Auth::id();
        $rowsCount = count($request->input('vehicles'));

        $tallerId = Auth::user()->effectiveTallerId();
        $currentVehicles = DB::table('vehicles')->whereIn('user_id', Auth::user()->teamUserIds())->count();
        $taller = DB::table('users')->where('id', '=', $tallerId)->first();

        if (($currentVehicles + $rowsCount) > $taller->cant_vehicle) {
            return response()->json('¡Error, Ya no puede crear mas vehiculos!', 422);
        }

        $created = DB::transaction(function () use ($request, $clientId, $userId) {
            $count = 0;

            foreach ($request->input('vehicles') as $row) {
                $vehicle = Vehicle::create([
                    'user_id' => $userId,
                    'client_id' => $clientId,
                    'patent' => $row['patent'],
                    'tipo' => $row['tipo'] ?? null,
                    'numero_interno' => $row['numero_interno'] ?? null,
                    // chasis/brand/model/engine son NOT NULL en la tabla vehicles; se guarda
                    // vacio si el usuario no lo completa en la carga masiva.
                    'chasis' => $row['chasis'] ?? '',
                    'brand' => $row['brand'] ?? '',
                    'model' => $row['model'] ?? '',
                    'engine' => $row['engine'] ?? '',
                    'year' => $row['year'] ?? null,
                    'color' => $row['color'] ?? null,
                    'km' => $row['km'] ?? null,
                    'horometro' => $row['horometro'] ?? null,
                ]);

                $this->attachMotor($vehicle, $row['motor_number'] ?? null, $row['motor_model'] ?? null, $row['arreglo_cpl'] ?? null);

                $count++;
            }

            return $count;
        });

        return response()->json(['created' => $created]);
    }

    /**
     * Actualiza el motor vigente del vehiculo (o lo crea si no tenia), usado al editar.
     */
    private function syncMotor(Request $request, Vehicle $vehicle)
    {
        $motorNumber = $request->input('motor_number');
        $motorModel = $request->input('motor_model');
        $arregloCpl = $request->input('arreglo_cpl');

        if (blank($motorNumber) && blank($motorModel) && blank($arregloCpl)) {
            return;
        }

        $currentAssignment = $vehicle->currentMotor;

        if ($currentAssignment && $currentAssignment->motor) {
            $currentAssignment->motor->update([
                'motor_number' => $motorNumber ?: null,
                'modelo_motor' => $motorModel ?: null,
                'arreglo_cpl' => $arregloCpl ?: null,
            ]);

            return;
        }

        $this->attachMotor($vehicle, $motorNumber, $motorModel, $arregloCpl);
    }

    private function attachMotorIfProvided(Request $request, Vehicle $vehicle)
    {
        $this->attachMotor(
            $vehicle,
            $request->input('motor_number'),
            $request->input('motor_model'),
            $request->input('arreglo_cpl')
        );
    }

    private function attachMotor(Vehicle $vehicle, $motorNumber, $motorModel, $arregloCpl)
    {
        if (blank($motorNumber) && blank($motorModel) && blank($arregloCpl)) {
            return;
        }

        $motor = Motor::create([
            'motor_number' => $motorNumber ?: null,
            'modelo_motor' => $motorModel ?: null,
            'arreglo_cpl' => $arregloCpl ?: null,
        ]);

        MotorAssignment::create([
            'motor_id' => $motor->id,
            'vehicle_id' => $vehicle->id,
            'fecha_inicio' => now(),
            'fecha_fin' => null,
        ]);
    }
}
