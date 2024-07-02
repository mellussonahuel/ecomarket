<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;


class FixImageName extends Migration
{
 
    public function up()
    {
        $products = Product::all();
        
   
        $products->map(function ($product) {
            $product->images->map(function($image){
                
                try{
                    if(Storage::disk('productos')->get($image->name)){
                        $info = pathinfo($image->name);
                        $ext = $info['extension'];
                        $newName = md5(preg_replace('/\s+/', '', time().$image->name)).'.'.$ext;
                        Storage::disk('productos')->move($image->name, $newName);
                        $image->name = $newName;
                        $image->save();
                    }else{
                        logger('else no cambia la imagen id:'.$image->id);    
                    }
                }catch(\Exception $e){
                    logger('catch no cambia la imagen id:'.$image->id);
                    logger($e->getMessage());
                   
                }

                
               
            });
        });
    }

  
}
