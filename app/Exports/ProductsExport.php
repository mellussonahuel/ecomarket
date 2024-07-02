<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $collection = new Collection();
        Product::get()->map( function($p) use ($collection){
            $collection->push([$p->id,$p->title,$p->price,$p->stock,$p->sale_type]);
            
            if($p->variants) 
             $p->variants->map(function($v) use ($collection,$p){
                $collection->push([$v->id,$p->title .' '. $v->weight,$v->price,'-',$p->sale_type, 'variante']);
             });
        });
        
        return $collection;
    }

    public function headings(): array
    {
        return ['id','title','price','stock','tipo venta','tipo'];
    }
}