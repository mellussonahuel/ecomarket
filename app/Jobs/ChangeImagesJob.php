<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ChangeImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $products;
    public function __construct()
    {
        $this->products = Product::all(); 
    }

   
    public function handle()
    {
        
        
   
        $this->products->map(function ($product) {
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
                        // logger('else no cambia la imagen id:'.$image->id);    
                    }
                }catch(\Exception $e){
                    // logger('no cambia la imagen id:'.$image->id);
                    
                }

                
               
            });
        });
    }
}
