<?php

namespace App\Http\Controllers;

use App\Models\VehicleYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class VehicleYearController extends Controller
{
    public function all($model)
    {
        $years = VehicleYear::select(DB::raw('vehicle_years.id as id, 
                                                  vehicle_years.v_year as year'))
            ->join('vehicle_models', 'vehicle_models.id', '=', 'vehicle_years.v_id')
            ->where('vehicle_models.id', '=', $model)
            ->orderBy('v_year', 'ASC')
            ->get();

        return $years;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'v_year' => 'required|max:4|min:4'
        ], [
            'v_year.required' => 'El campo año de inicio es obligatorio',
            'v_year.min' => 'El campo año de inicio debe tener al menos 4 caracteres',
            'v_year.max' => 'El campo año de inicio debe tener a lo más 4 caracteres'
        ]);


        $years = DB::table('vehicle_years')->where([
            ['v_id', '=', $request->v_id],
            ['v_year', '=', $request->v_year]
        ])
            ->get();
        if (!$years->isEmpty()) {
            return response()->json([
                'errors' => [
                    'v_year' => 'El año y modelo, ya existen'
                ]
            ], 422);
        } else {
            $data = $request->all();
            VehicleYear::create($data);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'v_year' => 'required|min:4|max:4'
        ], [
            'v_year.required' => 'El campo año de inicio es obligatorio',
            'v_year.min' => 'El campo año de inicio debe tener al menos 4 caracteres',
            'v_year.max' => 'El campo año de inicio debe tener a lo más 4 caracteres'
        ]);

        VehicleYear::find($id)->update($request->all());

        return;
    }

    public function all_year()
    {

        $years = VehicleYear::select(DB::raw('vehicle_years.id as id,
                                              vehicle_years.v_year as year,
                                              vehicle_models.model as model,
                                              vehicle_brands.brand as brand'))
            ->join('vehicle_models', 'vehicle_models.id', '=', 'vehicle_years.v_id')
            ->join('vehicle_brands', 'vehicle_brands.id', '=', 'vehicle_models.brand_id')
            ->paginate(10);

        return [
            'pagination_year' => [
                'total'         => $years->total(),
                'current_page'  => $years->currentPage(),
                'per_page'      => $years->perPage(),
                'last_page'     => $years->lastPage(),
                'from'          => $years->firstItem(),
                'to'            => $years->lastItem(),
            ],
            'vehicleyears' => $years
        ];
    }

    public function selectYears()
    {
        $years = VehicleYear::orderBy('id', 'ASC')->get();

        return $years;
    }

    public function ym($model)
    {
        $years = VehicleYear::select('id', 'v_year')->where('v_id', '=', $model)->get();

        return $years;
    }
}
