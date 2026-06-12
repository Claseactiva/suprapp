<?php

namespace App\Http\Controllers;

use App\Models\Detailimport;
use Illuminate\Http\Request;

class DetailimportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detailimports = Detailimport::orderBy('id', 'DESC')->paginate((int) request('per_page', 20));

        return $detailimports;
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

        // return $data['price'] * $this->convertCurrency($data['price'], 'CLP', 'USD');

        // // Detailimport::create([
        // //     'aditional' => $data['aditional'],
        // //     'detail' => $data['detail'],
        // //     'import_id' => $data['import_id'],
        // //     'price_dolar' => $this->convertCurrency($data['price'], 'CLP', 'USD') * $data['dolar'],
        // //     'price' => $data['price'],
        // //     'product' => $data['product'],
        // //     'quantity' => $data['quantity'],
        // //     'seguro' => $data['seguro'],
        // //     'usa' => $data['usa'],
        // //     'valorem' => $data['valorem']

        // // ]);

        Detailimport::create($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Detailimport  $detailimport
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $detailimport = Detailimport::find($id);

        return $detailimport;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Detailimport  $detailimport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Detailimport::find($id)->update($request->all());

        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Detailimport  $detailimport
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $detailimport = Detailimport::findOrFail($id);
        $detailimport->delete();

        return;
    }

    


}
