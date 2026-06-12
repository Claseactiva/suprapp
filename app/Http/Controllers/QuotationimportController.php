<?php

namespace App\Http\Controllers;

use App\Models\Quotationimport;
use App\Models\Import;
use App\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class QuotationimportController extends Controller
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
            $quotationimports = Quotationimport::orderBy('id', 'DESC')->paginate(10);
        else
            $quotationimports = Quotationimport::orderBy('id', 'DESC')->where('user_id', '=', $user_id)->paginate(10);

        return [
            'pagination' => [
                'total'         => $quotationimports->total(),
                'current_page'  => $quotationimports->currentPage(),
                'per_page'      => $quotationimports->perPage(),
                'last_page'     => $quotationimports->lastPage(),
                'from'          => $quotationimports->firstItem(),
                'to'            => $quotationimports->lastItem(),
            ],
            'quotationimports' => $quotationimports
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

        $quotation = Quotationimport::create($data);

        return $quotation->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quotationimport  $quotationimport
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quotationimport = Quotationimport::find($id);

        return $quotationimport;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quotationimport  $quotationimport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Quotationimport::find($id)->update($request->all());

        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quotationimport  $quotationimport
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quotationimport = Quotationimport::findOrFail($id);
        $quotationimport->delete();

        return;
    }

    public function pdf($id)
    {
        $quotation = Quotationimport::find($id);

        $user = User::find($quotation->user_id);

        $import = Quotationimport::find($id)->import;

        $products = Import::find($import->id)->detailimports;

        $client = Quotationimport::find($id)->client;

        $pdf = pdf::loadView('pdf.quotationimport', compact(['user', 'quotation', 'import', 'client', 'products']));

        return $pdf->download('cotizacion de importación.pdf');
    }
}
