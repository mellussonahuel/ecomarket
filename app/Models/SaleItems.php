<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItems extends Model
{
    protected $table = "sales_item";

    protected $with = [
        'variant'
    ];

    public function getItemIdAttribute(){
        return $this->attributes['variant_id'] ?? $this->attributes['id'];
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function variant(){
        return $this->hasOne(ProductVariant::class,'id','variant_id');
    }

    public function getVariantTotalAttribute(){
       
        if(!$this->attributes['variant_id']){
            return $this->attributes['qty'];
        }else{
            $totalWeight = $this->variant->weight * $this->attributes['qty'];
            return $totalWeight >= 1000 ? $totalWeight /1000 . ' Kg.' : $totalWeight . ' Gr.'; 
        }
        
        return null;
    }
}
