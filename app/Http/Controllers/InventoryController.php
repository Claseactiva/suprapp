<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $id_user = Auth::id();
        $inventories = Product::with('inventories', 'client')->name()->withUserClients($id_user)->paginate(10);

        return [
            'pagination' => [
                'total'         => $inventories->total(),
                'current_page'  => $inventories->currentPage(),
                'per_page'      => $inventories->perPage(),
                'last_page'     => $inventories->lastPage(),
                'from'          => $inventories->firstItem(),
                'to'            => $inventories->lastItem(),
            ],
            'inventories' => $inventories
        ];
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'price' => 'required',
            'quantity' => 'required',
        ], [
            'price.required' => 'El campo precio es obligatorio',
            'quantity.required' => 'El campo cantidad es obligatorio',
        ]);

        $inventory = Inventory::create($request->all());

        return $inventory;
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();

        return $inventory;
    }
}
