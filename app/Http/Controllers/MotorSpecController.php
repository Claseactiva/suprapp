<?php

namespace App\Http\Controllers;

use App\Models\MotorSpec;
use App\Models\VehicleEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MotorSpecController extends Controller
{
    public function index()
    {
        $search = request('search');

        $query = MotorSpec::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('raw_label', 'LIKE', "%{$search}%")
                    ->orWhere('combustible', 'LIKE', "%{$search}%")
                    ->orWhere('cilindrada', 'LIKE', "%{$search}%");
            });
        }

        $specs = $query->orderBy('cilindrada')->orderBy('raw_label')->paginate((int) request('per_page', 20));

        return [
            'pagination_motorspec' => [
                'total'         => $specs->total(),
                'current_page'  => $specs->currentPage(),
                'per_page'      => $specs->perPage(),
                'last_page'     => $specs->lastPage(),
                'from'          => $specs->firstItem(),
                'to'            => $specs->lastItem(),
            ],
            'motorspecs' => $specs
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'cilindrada' => 'required|numeric|min:0|max:99.99',
            'combustible' => 'required|in:BENCINA,DIESEL,ELECTRICO',
        ], [
            'cilindrada.required' => 'La cilindrada es obligatoria',
            'cilindrada.numeric' => 'La cilindrada debe ser un numero',
            'combustible.required' => 'El combustible es obligatorio',
            'combustible.in' => 'El combustible debe ser BENCINA, DIESEL o ELECTRICO',
        ]);

        $cilindrada = round((float) $request->input('cilindrada'), 2);
        $combustible = $request->input('combustible');

        if (MotorSpec::where('cilindrada', $cilindrada)->where('combustible', $combustible)->exists()) {
            return response()->json([
                'errors' => ['cilindrada' => 'Esa especificacion de motor ya existe'],
            ], 422);
        }

        return MotorSpec::create([
            'cilindrada' => $cilindrada,
            'combustible' => $combustible,
            'raw_label' => number_format($cilindrada, 1) . ' ' . $combustible,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'raw_label' => 'required|min:1|max:90',
        ], [
            'raw_label.required' => 'El texto del motor es obligatorio',
        ]);

        $spec = MotorSpec::findOrFail($id);
        $spec->update(['raw_label' => $request->input('raw_label')]);

        return $spec;
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();

        if (VehicleEngine::where('motor_spec_id', $id)->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar: hay motores en uso con esta especificacion',
            ], 422);
        }

        MotorSpec::findOrFail($id)->delete();

        return response()->json(['message' => 'Especificacion eliminada correctamente']);
    }

    public function selectMotorSpecs()
    {
        return MotorSpec::orderBy('cilindrada')->orderBy('raw_label')->get();
    }

    private function authorizeAdmin()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }
    }
}
