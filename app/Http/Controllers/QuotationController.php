<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        /*if($user_id == 1 || $user_id == 2)
            $quotations = Quotation::orderBy('id', 'DESC')->paginate(10);
        else*/
        $quotations = Quotation::orderBy('id', 'DESC')->where('user_id', '=', $user_id)->paginate(10);

        return [
            'pagination' => [
                'total'         => $quotations->total(),
                'current_page'  => $quotations->currentPage(),
                'per_page'      => $quotations->perPage(),
                'last_page'     => $quotations->lastPage(),
                'from'          => $quotations->firstItem(),
                'to'            => $quotations->lastItem(),
            ],
            'quotations' => $quotations
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

        Quotation::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quotation = Quotation::find($id);

        return $quotation;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Quotation::find($id)->update($request->all());

        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();

        return;
    }

    public function details($id)
    {
        return Quotation::findOrFail($id)->details;
    }

    public function pdf($id)
    {
        /**
         * toma en cuenta que para ver los mismos
         * datos debemos hacer la misma consulta
        **/
        $products = Quotation::findOrFail($id)->details;
        $quotation = Quotation::find($id);

        $pdf = PDF::loadView('pdf.quotation', compact(['quotation', 'products']));

        return $pdf->download('cotizacion.pdf');
    }
}
