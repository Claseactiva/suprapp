<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductImport implements ToCollection
{
    public $products;
 
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
        
            $this->products[] = Product::create([
                'name' => $row[0],
                'detail' => $row[1]                
            ]);
        }
    }
}
