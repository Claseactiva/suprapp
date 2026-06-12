<?php

namespace App\Http\Controllers;

use App\Models\QuotationUserDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationUserDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotations = DB::table('quotation_user_descriptions')
                        ->join('quotation_users', 'quotation_users.id', '=', 'quotation_user_descriptions.user_id')
                        ->join('quotation_user_vehicles', function($join){
                            $join->on('quotation_user_vehicles.id', '=', 'quotation_user_descriptions.vehicle_id');
                            $join->on('quotation_user_vehicles.user_id', '=', 'quotation_users.id');
                        })
                        ->select(
                            'quotation_user_descriptions.id',
                            'quotation_user_descriptions.is_completed',
                            'quotation_users.name',
                            'quotation_users.email',
                            'quotation_users.phone',
                            'quotation_user_vehicles.patentchasis',
                            'quotation_user_vehicles.brand',
                            'quotation_user_vehicles.model',
                            'quotation_user_vehicles.year',
                            'quotation_user_vehicles.engine',
                            'quotation_user_descriptions.description',
                            'quotation_user_descriptions.created_at'
                        )
                        ->where('quotation_user_descriptions.is_completed','=',0)
                        ->orderBy('quotation_user_descriptions.created_at', 'DESC')
                        ->paginate(20);
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuotationUserDescription  $quotationUserDescription
     * @return \Illuminate\Http\Response
     */
    public function show(QuotationUserDescription $quotationUserDescription)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuotationUserDescription  $quotationUserDescription
     * @return \Illuminate\Http\Response
     */
    public function edit(QuotationUserDescription $quotationUserDescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuotationUserDescription  $quotationUserDescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuotationUserDescription $quotationUserDescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuotationUserDescription  $quotationUserDescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuotationUserDescription $quotationUserDescription)
    {
        //
    }
}
