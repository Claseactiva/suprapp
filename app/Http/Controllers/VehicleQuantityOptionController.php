<?php

namespace App\Http\Controllers;

use App\Models\VehicleQuantityOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleQuantityOptionController extends Controller
{
    public function index()
    {
        return VehicleQuantityOption::orderBy('value')->get();
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $this->validate($request, [
            'value' => 'required|integer|min:1|unique:vehicle_quantity_options,value',
        ], [
            'value.required' => 'El campo cantidad es obligatorio',
            'value.integer' => 'La cantidad debe ser un numero',
            'value.min' => 'La cantidad debe ser mayor a 0',
            'value.unique' => 'Esa cantidad ya existe',
        ]);

        return VehicleQuantityOption::create([
            'value' => $request->input('value'),
        ]);
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();

        VehicleQuantityOption::findOrFail($id)->delete();

        return response()->json(['message' => 'Opcion eliminada correctamente']);
    }

    private function authorizeAdmin()
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(403);
        }
    }
}
