<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use League\CommonMark\Reference\Reference;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class ProductsImports implements ToCollection, WithChunkReading
{
    
    public function collection(Collection $rows)
    {
   
        foreach($rows as $key => $row){
           
            if($row[5] === 'variante'){
                
                ProductVariant::find($row[0])
                            ->update(['price' => $row[2] ]);

            }else{
                    $product = Product::find($row[0]);
                    if($product){
                        $product->price = $row[2];
                        $product->stock = $row[3] ?? 0;
                        $product->save();    
                    } 
                    
            }
        }
    }

    public function chunkSize(): int
    {
        return 700;
    }
}