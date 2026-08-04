<?php

namespace App\Http\Controllers;

use App\Models\VehicleEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleEngineController extends Controller
{
    /**
     * Anios disponibles para un modelo, calculados expandiendo los rangos
     * de sus motores (reemplaza a VehicleYearController::all/ym).
     */
    public function years($model)
    {
        $ranges = VehicleEngine::where('vehicle_model_id', $model)
            ->get(['year_from', 'year_to']);

        $years = collect();
        foreach ($ranges as $range) {
            for ($y = $range->year_from; $y <= $range->year_to; $y++) {
                $years->push($y);
            }
        }

        return $years->unique()->sort()->values()->map(function ($y) {
            return ['id' => $y, 'year' => $y];
        });
    }

    /**
     * Motores cuyo rango de anios contiene $year, para el modelo dado.
     */
    public function all($model, $year)
    {
        $engines = VehicleEngine::select(DB::raw('vehicle_engines.motor_spec_id as id,
                                                    motor_specs.raw_label as engine'))
            ->join('motor_specs', 'motor_specs.id', '=', 'vehicle_engines.motor_spec_id')
            ->where('vehicle_engines.vehicle_model_id', $model)
            ->where('vehicle_engines.year_from', '<=', $year)
            ->where('vehicle_engines.year_to', '>=', $year)
            ->get();

        return $engines;
    }

    /**
     * Agrega un anio a un modelo+motor: extiende un rango existente si es
     * adyacente, fusiona dos rangos si rellena un hueco entre ambos, o crea
     * un rango nuevo de un solo anio si no hay nada cercano.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'vehicle_model_id' => 'required|integer',
            'motor_spec_id' => 'required|integer',
            'year' => 'required|integer|min:1900|max:2100',
        ], [
            'vehicle_model_id.required' => 'El modelo es obligatorio',
            'motor_spec_id.required' => 'El motor es obligatorio',
            'year.required' => 'El anio es obligatorio',
        ]);

        $modelId = (int) $request->input('vehicle_model_id');
        $specId = (int) $request->input('motor_spec_id');
        $year = (int) $request->input('year');

        $exists = VehicleEngine::where('vehicle_model_id', $modelId)
            ->where('motor_spec_id', $specId)
            ->where('year_from', '<=', $year)
            ->where('year_to', '>=', $year)
            ->exists();

        if ($exists) {
            return response()->json([
                'errors' => ['year' => 'El anio, modelo y motor ya existen'],
            ], 422);
        }

        return DB::transaction(function () use ($modelId, $specId, $year) {
            $lower = VehicleEngine::where('vehicle_model_id', $modelId)
                ->where('motor_spec_id', $specId)
                ->where('year_to', $year - 1)
                ->first();

            $upper = VehicleEngine::where('vehicle_model_id', $modelId)
                ->where('motor_spec_id', $specId)
                ->where('year_from', $year + 1)
                ->first();

            if ($lower && $upper) {
                $upper->year_from = $lower->year_from;
                $upper->save();
                $lower->delete();

                return $upper;
            }

            if ($lower) {
                $lower->year_to = $year;
                $lower->save();

                return $lower;
            }

            if ($upper) {
                $upper->year_from = $year;
                $upper->save();

                return $upper;
            }

            return VehicleEngine::create([
                'vehicle_model_id' => $modelId,
                'motor_spec_id' => $specId,
                'year_from' => $year,
                'year_to' => $year,
            ]);
        });
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'motor_spec_id' => 'required|integer',
            'year_from' => 'required|integer|min:1900|max:2100',
            'year_to' => 'required|integer|min:1900|max:2100|gte:year_from',
        ], [
            'motor_spec_id.required' => 'El motor es obligatorio',
            'year_from.required' => 'El anio de inicio es obligatorio',
            'year_to.required' => 'El anio de termino es obligatorio',
            'year_to.gte' => 'El anio de termino debe ser mayor o igual al de inicio',
        ]);

        $engine = VehicleEngine::findOrFail($id);
        $engine->update($request->only(['motor_spec_id', 'year_from', 'year_to']));

        return $engine;
    }

    public function all_motors()
    {
        $search = request('search');

        $query = VehicleEngine::select(DB::raw('vehicle_engines.id as id,
                                              vehicle_brands.brand as brand,
                                              vehicle_models.model as model,
                                              vehicle_engines.motor_spec_id as motor_spec_id,
                                              vehicle_engines.year_from as year_from,
                                              vehicle_engines.year_to as year_to,
                                              motor_specs.raw_label as motor'))
            ->join('vehicle_models', 'vehicle_models.id', '=', 'vehicle_engines.vehicle_model_id')
            ->join('vehicle_brands', 'vehicle_brands.id', '=', 'vehicle_models.brand_id')
            ->join('motor_specs', 'motor_specs.id', '=', 'vehicle_engines.motor_spec_id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('vehicle_brands.brand', 'LIKE', "%{$search}%")
                    ->orWhere('vehicle_models.model', 'LIKE', "%{$search}%")
                    ->orWhere('motor_specs.raw_label', 'LIKE', "%{$search}%");
            });
        }

        $motors = $query->orderBy('vehicle_brands.brand')
            ->orderBy('vehicle_models.model')
            ->orderBy('vehicle_engines.year_from')
            ->paginate((int) request('per_page', 20));

        return [
            'pagination_motor' => [
                'total'         => $motors->total(),
                'current_page'  => $motors->currentPage(),
                'per_page'      => $motors->perPage(),
                'last_page'     => $motors->lastPage(),
                'from'          => $motors->firstItem(),
                'to'            => $motors->lastItem(),
            ],
            'vehiclemotors' => $motors
        ];
    }
}
