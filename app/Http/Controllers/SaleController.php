<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\Client;
use App\Models\ProductSale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $search = request('calendar');

        $query = Sale::with('productSales', 'productSales.product')->orderBy('total', 'DESC');

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        if ($search !== null) {
            $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') = ?", [$search]);
        }

        $sales = $query->paginate((int) request('per_page', 20));

        return [
            'pagination' => [
                'total'         => $sales->total(),
                'current_page'  => $sales->currentPage(),
                'per_page'      => $sales->perPage(),
                'last_page'     => $sales->lastPage(),
                'from'          => $sales->firstItem(),
                'to'            => $sales->lastItem(),
            ],
            'sales' => $sales

        ];
    }

    public function sale(Request $request)
    {
        $clients = Client::where('user_id', '=', Auth::user()->id)->where('type', '=', 'Empresa')->get();

        $sale_id = Sale::create([
            'user_id' => Auth::user()->id,
            'client_id' => $clients[0]->id,
            'forma_pago' => $request->formapago,
            'total' => $request->total,
            'descuento' => floatval($request->descuento / 100)
        ])->id;

        foreach ($request->cart as $key => $cart) {

            ProductSale::create([
                'sale_id' => $sale_id,
                'product_id' => $cart['id'],
                'quantity' => $cart['quantity'],
                'neto' => $cart['neto'],
                'total' => $cart['total'],
                'utility' => $cart['utilidad'],
            ]);

            $inventories = Inventory::where('product_id', $cart['id'])->orderBy('id', 'ASC')->get();

            foreach ($inventories as $key => $inventory) {
                // Calcula la cantidad a descontar para este producto
                $discountQuantity = min($cart['quantity'], $inventory['quantity']);


                // Aplica el descuento y actualiza la cantidad del producto
                $inventories[$key]['quantity'] -= $discountQuantity;
                $inventories[$key]['discount'] += $discountQuantity;

                // Actualiza el total a descontar
                $cart['quantity'] -= $discountQuantity;


                Inventory::where('id', $inventory['id'])->update([
                    'discount' => $inventory['discount'],
                    'quantity' => $inventory['quantity']
                ]);


                // Si ya se han descontado todos los productos necesarios, detén el bucle
                if ($cart['quantity'] <= 0) {
                    break;
                }
            }
        }
    }

    public function anularSale($id)
    {
        try {
            $products = ProductSale::where('sale_id', $id)->get();
            foreach ($products as $product) {
                $inventories =  Inventory::where('product_id', $product['product_id'])->orderBy('id', 'ASC')->get();
                foreach ($inventories as $inventory) {
                    if ($inventory['discount'] > 0 && $inventory['discount'] >= $product['quantity']) {
                        Inventory::where('id', $inventory['id'])->update([
                            'discount' => $inventory['discount'] - $product['quantity'],
                            'quantity' => $inventory['quantity'] + $inventory['discount'],
                        ]);
                    }
                }
                ProductSale::where('sale_id', $id)
                    ->where('product_id', $product['product_id'])
                    ->delete();
            }

            Sale::where('id', $id)->delete();

            // Devolver una respuesta indicando éxito
            return response()->json(['status' => true], 200);
        } catch (\Exception $e) {
            // Devolver una respuesta indicando el error
            return response()->json(['status' => false], 500);
        }
    }

    public function all()
    {
        $id_user = Auth::id();
        $products = Product::with('inventories', 'client')->withUserClients($id_user)->get();
        return $products;
    }

    public function generarRecibo($id)
    {
        $sales = Sale::with('productSales', 'client', 'user', 'productSales.product')->where('id', '=', $id)->get();
        $pdf = PDF::loadView('pdf.sales-recibo', compact('sales'))->setPaper('B8', 'portrait');
        return $pdf->stream('Recibo N° ' . $id . '.pdf');
    }

    public function cierreCajaZ($fecha)
    {
        $user = Auth::user();
        $query = Sale::with('productSales', 'client', 'user', 'productSales.product')->whereDate('created_at', $fecha);

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        $sales = $query->get();

        if (count($sales) > 0) {
            $pdf = pdf::loadView('pdf.cierre-cajaz', compact('sales', 'fecha'));
            return $pdf->stream('CajaZ-' . $fecha . '.pdf');
        } else {
            return response()->json(['error' => 0], 200);
        }
    }
}
