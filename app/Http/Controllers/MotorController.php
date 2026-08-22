<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\MotorAssignment;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MotorController extends Controller
{
    public function index()
    {
        $search = request('search');
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        $teamIds = $user->teamUserIds();

        $query = Motor::query()
            ->leftJoin('motor_assignments', function ($join) {
                $join->on('motor_assignments.motor_id', '=', 'motors.id')
                    ->whereNull('motor_assignments.fecha_fin');
            })
            ->leftJoin('vehicles', 'vehicles.id', '=', 'motor_assignments.vehicle_id')
            ->leftJoin('clients', 'clients.id', '=', 'motors.client_id')
            ->select(
                'motors.id as id',
                'motors.motor_number as motor_number',
                'motors.numero_interno as numero_interno',
                'motors.modelo_motor as modelo_motor',
                'motors.arreglo_cpl as arreglo_cpl',
                'motors.created_by as created_by',
                'motors.client_id as client_id',
                'clients.razonSocial as client_name',
                'vehicles.id as vehicle_id',
                'vehicles.patent as vehicle_patent',
                'vehicles.brand as vehicle_brand',
                'vehicles.model as vehicle_model'
            );

        if (!$isAdmin) {
            $accessibleClientIds = $user->companies()->pluck('clients.id');
            $query->where(function ($q) use ($teamIds, $accessibleClientIds) {
                $q->where(function ($qq) use ($teamIds) {
                    // Motores sueltos: solo si los creo alguien del equipo, o son
                    // de antes de que existiera created_by (dueno desconocido).
                    $qq->whereNull('motor_assignments.vehicle_id')
                        ->where(function ($qqq) use ($teamIds) {
                            $qqq->whereNull('motors.created_by')
                                ->orWhereIn('motors.created_by', $teamIds);
                        });
                })
                    ->orWhereIn('vehicles.user_id', $teamIds)
                    ->orWhereIn('vehicles.client_id', $accessibleClientIds)
                    ->orWhereIn('motors.client_id', $accessibleClientIds);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('motors.motor_number', 'LIKE', "%{$search}%")
                    ->orWhere('motors.arreglo_cpl', 'LIKE', "%{$search}%")
                    ->orWhere('vehicles.patent', 'LIKE', "%{$search}%");
            });
        }

        $motors = $query->orderBy('motors.id', 'DESC')->paginate((int) request('per_page', 20));

        $motors->getCollection()->transform(function ($motor) use ($isAdmin, $teamIds) {
            $motor->can_edit = $isAdmin || $motor->created_by === null || in_array($motor->created_by, $teamIds);
            return $motor;
        });

        return [
            'pagination_motors' => [
                'total'         => $motors->total(),
                'current_page'  => $motors->currentPage(),
                'per_page'      => $motors->perPage(),
                'last_page'     => $motors->lastPage(),
                'from'          => $motors->firstItem(),
                'to'            => $motors->lastItem(),
            ],
            'motors' => $motors
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'motor_number' => 'nullable|string|max:100',
            'numero_interno' => 'nullable|string|max:100',
            'modelo_motor' => 'nullable|string|max:190',
            'arreglo_cpl' => 'nullable|string|max:100',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        if (!$request->filled('motor_number') && !$request->filled('arreglo_cpl')) {
            return response()->json([
                'errors' => ['motor_number' => 'Debe ingresar al menos el numero de motor o el arreglo/CPL'],
            ], 422);
        }

        return Motor::create($request->only(['motor_number', 'numero_interno', 'modelo_motor', 'arreglo_cpl', 'client_id']) + [
            'created_by' => Auth::id(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $motor = Motor::findOrFail($id);
        $this->authorizeMotorAccess($motor);

        $this->validate($request, [
            'motor_number' => 'nullable|string|max:100',
            'numero_interno' => 'nullable|string|max:100',
            'modelo_motor' => 'nullable|string|max:190',
            'arreglo_cpl' => 'nullable|string|max:100',
            'client_id' => 'nullable|exists:clients,id',
        ]);

        $motor->update($request->only(['motor_number', 'numero_interno', 'modelo_motor', 'arreglo_cpl', 'client_id']));

        return $motor;
    }

    /**
     * Motores de la flota de un cliente: los que tiene ligados directamente
     * (client_id, sueltos o no) mas los que estan instalados ahora mismo en
     * alguno de sus vehiculos.
     */
    public function byClient($clientId)
    {
        $client = \App\Models\Client::findOrFail($clientId);
        $this->authorizeClientAccess($client);

        return Motor::query()
            ->leftJoin('motor_assignments', function ($join) {
                $join->on('motor_assignments.motor_id', '=', 'motors.id')
                    ->whereNull('motor_assignments.fecha_fin');
            })
            ->leftJoin('vehicles', 'vehicles.id', '=', 'motor_assignments.vehicle_id')
            ->where(function ($q) use ($clientId) {
                $q->where('motors.client_id', $clientId)
                    ->orWhere('vehicles.client_id', $clientId);
            })
            ->select(
                'motors.id as id',
                'motors.motor_number as motor_number',
                'motors.numero_interno as numero_interno',
                'motors.modelo_motor as modelo_motor',
                'motors.arreglo_cpl as arreglo_cpl',
                'motors.client_id as client_id',
                'vehicles.id as vehicle_id',
                'vehicles.patent as vehicle_patent',
                'vehicles.brand as vehicle_brand',
                'vehicles.model as vehicle_model',
                'vehicles.year as vehicle_year'
            )
            ->orderBy('motors.id', 'DESC')
            ->get();
    }

    public function link(Request $request, $id)
    {
        $this->validate($request, [
            'patent' => 'required|string',
        ], [
            'patent.required' => 'Debe indicar la patente del vehiculo',
        ]);

        $motor = Motor::findOrFail($id);
        $this->authorizeMotorAccess($motor);

        $vehicle = Vehicle::whereRaw('UPPER(patent) = ?', [strtoupper(trim($request->input('patent')))])->first();

        if (!$vehicle) {
            return response()->json([
                'errors' => ['patent' => 'No existe un vehiculo con esa patente'],
            ], 422);
        }

        $user = Auth::user();
        if (!$user->hasRole('admin') && !in_array($vehicle->user_id, $user->teamUserIds())) {
            return response()->json([
                'errors' => ['patent' => 'No tienes acceso a ese vehiculo'],
            ], 422);
        }

        return DB::transaction(function () use ($motor, $vehicle) {
            $now = now();

            MotorAssignment::where('motor_id', $motor->id)
                ->whereNull('fecha_fin')
                ->where('vehicle_id', '!=', $vehicle->id)
                ->update(['fecha_fin' => $now]);

            MotorAssignment::where('vehicle_id', $vehicle->id)
                ->whereNull('fecha_fin')
                ->where('motor_id', '!=', $motor->id)
                ->update(['fecha_fin' => $now]);

            $already = MotorAssignment::where('motor_id', $motor->id)
                ->where('vehicle_id', $vehicle->id)
                ->whereNull('fecha_fin')
                ->exists();

            if (!$already) {
                MotorAssignment::create([
                    'motor_id' => $motor->id,
                    'vehicle_id' => $vehicle->id,
                    'fecha_inicio' => $now,
                    'fecha_fin' => null,
                ]);
            }

            return MotorAssignment::with('vehicle')
                ->where('motor_id', $motor->id)
                ->whereNull('fecha_fin')
                ->first();
        });
    }

    public function unlink($id)
    {
        $motor = Motor::findOrFail($id);
        $this->authorizeMotorAccess($motor);

        MotorAssignment::where('motor_id', $motor->id)
            ->whereNull('fecha_fin')
            ->update(['fecha_fin' => now()]);

        return response()->json(['message' => 'Motor desvinculado correctamente']);
    }

    public function history($id)
    {
        Motor::findOrFail($id);

        return MotorAssignment::with('vehicle')
            ->where('motor_id', $id)
            ->orderBy('fecha_inicio', 'DESC')
            ->get();
    }

    public function destroy($id)
    {
        $motor = Motor::findOrFail($id);
        $this->authorizeMotorAccess($motor);

        $motor->delete();

        return response()->json(['message' => 'Motor eliminado correctamente']);
    }

    /**
     * Solo quien creo el motor (o alguien de su equipo) puede editarlo/eliminarlo/
     * vincularlo; un mecanico vinculado a una empresa solo ve/opera lo que el
     * mismo creo, no lo que creo el taller. Los motores de antes de que existiera
     * created_by (null) mantienen el chequeo anterior via el vehiculo asignado.
     */
    private function authorizeMotorAccess(Motor $motor)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if ($user->hasRole('admin')) {
            return;
        }

        if ($motor->created_by !== null) {
            if (!in_array($motor->created_by, $user->teamUserIds())) {
                abort(403);
            }
            return;
        }

        $activeVehicleId = MotorAssignment::where('motor_id', $motor->id)
            ->whereNull('fecha_fin')
            ->value('vehicle_id');

        if ($activeVehicleId === null) {
            return;
        }

        $vehicle = Vehicle::find($activeVehicleId);

        if (!$vehicle || !in_array($vehicle->user_id, $user->teamUserIds())) {
            abort(403);
        }
    }

    /**
     * Solo el admin, el dueno (taller) del cliente, o un mecanico vinculado
     * a ese cliente pueden ver la flota de motores de ese cliente.
     */
    private function authorizeClientAccess(\App\Models\Client $client)
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $client->user_id === $user->id) {
            return;
        }

        if ($client->mechanics()->where('mechanic_id', $user->id)->exists()) {
            return;
        }

        abort(403, 'No tienes acceso a la flota de este cliente.');
    }
}
