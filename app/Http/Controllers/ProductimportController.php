<?php

namespace App\Http\Controllers;

use App\Models\Productimport;
use Illuminate\Http\Request;

class ProductimportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Productimport::orderBy('id', 'DESC')->paginate(10);

        return [
            'pagination' => [
                'total'         => $products->total(),
                'current_page'  => $products->currentPage(),
                'per_page'      => $products->perPage(),
                'last_page'     => $products->lastPage(),
                'from'          => $products->firstItem(),
                'to'            => $products->lastItem(),
            ],
            'products' => $products
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

        $product = Productimport::create($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Productimport  $productimport
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Productimport::find($id);

        return $product;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Productimport  $productimport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Productimport::find($id)->update($request->all());

        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Productimport  $productimport
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Productimport::findOrFail($id);
        $product->delete();

        return;
    }


    public function all()
    {
        $product = Productimport::orderBy('id', 'DESC')->get();

        return $product;
    }
}
