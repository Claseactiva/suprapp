<?php

namespace App\Http\Controllers;

use App\Models\RepairActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepairActivityController extends Controller
{
    public function index()
    {
        $search = request('search');

        $query = RepairActivity::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        return $query->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $this->validate($request, [
            'name' => 'required|string|max:190',
            'price' => 'nullable|numeric|min:0',
        ], [
            'name.required' => 'El nombre de la actividad es obligatorio',
        ]);

        if (RepairActivity::where('name', $request->input('name'))->exists()) {
            return response()->json([
                'errors' => ['name' => 'Esa actividad ya existe en la biblioteca'],
            ], 422);
        }

        return RepairActivity::create($request->only(['name', 'price']));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $this->validate($request, [
            'name' => 'required|string|max:190',
            'price' => 'nullable|numeric|min:0',
        ]);

        $activity = RepairActivity::findOrFail($id);
        $activity->update($request->only(['name', 'price']));

        return $activity;
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();

        RepairActivity::findOrFail($id)->delete();

        return response()->json(['message' => 'Actividad eliminada correctamente']);
    }

    private function authorizeAdmin()
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            abort(403);
        }
    }
}
