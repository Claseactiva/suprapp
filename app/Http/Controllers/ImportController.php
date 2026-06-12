<?php

namespace App\Http\Controllers;

use App\Models\Import;
use Illuminate\Http\Request;

use App\Exports\DetailimportsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();

        if($user_id == 1 || $user_id == 2)
            $imports = Import::orderBy('id', 'DESC')->paginate((int) request('per_page', 20));
        else
            $imports = Import::orderBy('id', 'DESC')->where('user_id', '=', $user_id)->paginate((int) request('per_page', 20));

        return [
            'pagination' => [
                'total'         => $imports->total(),
                'current_page'  => $imports->currentPage(),
                'per_page'      => $imports->perPage(),
                'last_page'     => $imports->lastPage(),
                'from'          => $imports->firstItem(),
                'to'            => $imports->lastItem(),
            ],
            'imports' => $imports
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $data['user_id'] = Auth::id();

        Import::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $import = Import::find($id);

        return $import;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Import::find($id)->update($request->all());

        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $import = Import::findOrFail($id);
        $import->delete();

        return;
    }

    public function details($id)
    {
        return Import::findOrFail($id)->detailimports;
    }

    public function export($id)
    {
        return Excel::download(new DetailimportsExport($id), 'detalle importación.xlsx');
    }

}
