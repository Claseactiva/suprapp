<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TipoPago;
use App\ProductPago;
use App\Models\Descuento;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $id_user = Auth::id();

        $products = Product::with('inventories', 'client')->name()->withUserClients($id_user)->paginate(10);

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


    public function store(Request $request)
    {
        $product = Product::create($request->all());

        return $product->id;
    }

    public function show($id)
    {
        $product = Product::find($id);

        return $product;
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $updated = $product->update($request->all());

        if ($request->atributo > 0) {
            $inventories = Inventory::where('product_id', $id)->get();
            if (!$inventories->isEmpty()) {
                foreach ($inventories as $inventory) {
                    if ($request->atributo !== $inventory->quantity) {
                        $cantidad = $inventory->quantity * $request->atributo;
                        $total = $inventory->price * $inventory->quantity;

                        Inventory::where('product_id', $id)->update([
                            'quantity' => $cantidad,
                            'price' => $total / $cantidad
                        ]);
                    }
                }
            }
        }

        if ($updated) {
            return $product;
        }

    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return $product;
    }

    public function all()
    {
        $id_user = Auth::id();

        $products = Product::with('inventories', 'client')->withUserClients($id_user)->get();
        return $products;
    }


    public function storeTipoPago(Request $request)
    {
        $data = $request->all();

        TipoPago::create([
            'pago' => $data['pago'],
            'utilidad' => 0
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listaTiposPagos()
    {
        $tipospagos = TipoPago::where('pago', '<>', 'DEFECTO')->get();

        return $tipospagos;
    }


    public function allPagos()
    {
        $pagos = TipoPago::where('pago', '<>', 'DEFECTO')->orderBy('id', 'ASC')->get();

        return $pagos;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function updateUtilidad(Request $request, $id)
    {
        TipoPago::find($id)->update($request->all());

        return;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDescuento(Request $request)
    {
        try {
            $data = $request->all();
            $idUser = Auth::id();

            $descuentos = Descuento::where('user_id', $idUser)->count();

            if ($descuentos > 0) {
                Descuento::where('user_id', $idUser)->update($request->all());
            } else {
                Descuento::create([
                    'user_id' => $idUser,
                    'descuento' => $data['descuento']
                ]);
            }

            // Additional code if needed after the try block

        } catch (\Exception $e) {
            // Handle the exception, you can log it or return an error response
            return response()->json(['error' => $e], 500);
        }
    }


    public function descuentoDefect()
    {
        $idUser = Auth::id();
        $descuento = Descuento::where('user_id', $idUser)->get();

        return $descuento;
    }
}
