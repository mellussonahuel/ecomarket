<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductCategory;
use Intervention\Image\Facades\Image;
use App\Models\ProductImage;
use phpDocumentor\Reflection\Types\Boolean;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Exports\ProductsExport;
use App\Imports\ProductsImports;

class ProductsController extends ResourceController
{
    protected $resourceModel = 'App\Models\Product';
    protected $paginate = true;
    protected $perPage= 12;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    
    
    
    
    protected function afterIndex($query)
    {   
        return $query->with('categories','images','variants');
    }

    public function getAll(Request $request){
        $query = Product::query();

        if($term = $request->term){
            $query->where( function($query) use ($term){
                return $query->where('title','LIKE', "%$term%")
                        ->orWhere('tags','LIKE', "%$term%")
                        ->orWhereHas('categories', function ($query) use ($term) {
                            $query->where('description', 'LIKE', "%$term%");
                        });
            });
        }

        if ((isset($this->paginate) && $this->paginate) || $request->perPage != 'false') {
            $result = $query->skip($request->page * $request->perPage)->paginate(intval($request->perPage));
        } else {
            $result = [ 'data' => $query->get() ];
        }

        return response()->json($result, 200);
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'description' => 'max:5000',
            'information' => 'max:5000',
            'price' => '',
            'sale_type' =>  'required_if:ask_price,==,false|string|max:5000' ,
            'stock' => [
                Rule::RequiredIf(function () use ($request) {
                    return $request->ask_type === false;
                 }),
                'sometimes',
                'integer'],
            'rating' => 'integer',
            'min_stock' => [
                Rule::RequiredIf(function () use ($request) {
                    return $request->ask_type === false;
                 }),
                'sometimes',
                'integer'],
            'categories' => 'required_if:ask_price,===,false',
            'discount'  => 'sometimes|numeric',
            'tags' => 'sometimes|max:255',
            'product_variants' => ''
        ]);
    

        //required_if:sale_type,==,Unidad|required_if:ask_price,==,false
        $validator->sometimes('price', 'required', function($request){
            return ($request->sale_type == 'Unidad' && !$request->ask_price == true);
        });

        $validator->sometimes('product_variants', 'required', function($request){
            return ($request->sale_type == 'Peso' && !$request->ask_price == true);
        });

        $validator->validate();

        $product = Product::create(
            $request->all(),
        );

        foreach($request->categories as $category){
           $product->categories()->attach($category['id']);
        }
        
        if($product->sale_type === 'Peso'){
            
            foreach($request->product_variants as $variant){
                
                ProductVariant::updateOrCreate([
                    'id' => $variant['id'] ?? 999999999999999999999999
                ],[
                    'product_id' => $product->id,
                    'weight' => $variant['weight'],
                    'price' => $variant['price'],
                    'active' => $variant['active']
                ]);
            }
        }
        
      
        return response()->json($product,200);
    }

    public function filter(Request $request)
    {
        
    
        $filters = collect(json_decode($request->filters));
          
        $hasCategories = $filters->filter(function($item){
            return $item->type == 'category';
        })->count();
        
        $query = Product::query();
        $query->with(['images','categories' , 'variants']);
       
        
        if($hasCategories){
            $categories = $this->getCategoriesToFilter($filters);
            if(count($categories)){
                $query->whereHas('categories', function($query) use ($categories){
                    $query->whereIn('category_id',$categories);
                });
            }
        }
        
        if($request->highlighted){
            $query->highlighted();
        }

        // combos filter
        $hasCombos = $filters->filter(function($item){
            return $item->type == 'sale-combo' && $item->parent == false;
        })->count();

        if($hasCombos){
            $query->where('is_combo','>',0);
        }

        // discount filter
        $hasDiscount = $filters->filter(function($item){
            return $item->type == 'sale' && $item->parent == false;
        })->count();

        if($hasDiscount){
            $query->where('discount','>',0)->orWhere('is_combo','>',0);
        }


        $term =  $filters->first(function($item){
            return $item->type == 'term' ;
        });

       
        
        if($term){
            $words = explode(' ',$term->label);

            $query->where( function($q) use ($words){      
                foreach($words as $word ){
                    $q->orWhere('description', 'LIKE', "$word%"); 
                    $q->orWhere('title', 'LIKE', "$word%");  
                }
      
                return $q;
            });
        }

        // $query->where('title','LIKE', "$word%")
        // ->orWhere('tags','LIKE', "$word%");
    // $query->orWhereHas('categories', function ($query) use ($word) {
    //     $query->where('description', 'LIKE', "$word%");
    // });    
        //dd($query->toSql());
        $query->active();
        $query->skip(intval($request->page) * intval($request->perPage ?? $this->perPage))->paginate(intval($request->perPage ?? $this->perPage));
        if($hasDiscount){
            $query->inRandomOrder();
        }
        $products = $query->paginate();    
        return response()->json($products, 200);
    }

    public function searchByTerm($term){
        $products = Product::search($term)->active()->take(10)->get();
        return response()->json($products, 200);
    }

    private function getCategoriesToFilter($filters){
        
        // separo categorias de padres
        $categories = $filters->filter(function($item){
            return $item->type == 'category' && $item->parent == false;
        })->pluck('id')->toArray();

        $parents =   $filters->filter(function($item){
                return $item->type == 'category' && $item->parent == true;
            })->pluck('id')->toArray();
        
        
        // obtengo los hijos
        $childrens = [];
        $all = [];
        if($parents !== null){
            $all = array_merge($childrens,$parents);
        }
            
        
        foreach($parents as $parent){
           $all =  array_merge($all, Category::where('parent_id',$parent)->get()->pluck('id')->toArray());
        }
        
        // junto todo
        $all = array_merge($all,$categories);
        
        // remuevo repetidos
        return array_unique($all);
            
    }

    public function getProductData(Request $request, $slug)
    {
        $slug = filter_var($slug, FILTER_SANITIZE_STRING);
        $query = Product::query();
       
        if($slug === 'combo-semanal'){
            $query->where('is_week_combo',1);
        }else{
            $query->where('slug', $slug);
        }
            
        $query->with('categories')
            ->with('images')
            ->with('variants');
           
        
        $product = $query->first();

        if(!$product){
            $result = ['data' => $product,'related' => []];
            return response()->json($result, 200);
        }

        $categoryIds = $product->categories->pluck('id')->toArray();
       
        $realted = Product::whereHas('categories', function ($query) use ($categoryIds) {
            return $query->whereIn('categories.id', $categoryIds);
        })->where('products.id','!=', $product->id)
            ->limit(10)
            ->with('images')
            ->with('categories')
            ->with('variants')
            ->get();


        $result = ['data' => $product,'related' => $realted];
        return response()->json($result, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
       

        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'description' => 'max:5000',
            'information' => 'max:5000',
            'price' => '',
            'sale_type' =>  'required_if:ask_price,==,false|string|max:5000' ,
            'stock' => [
                Rule::RequiredIf(function () use ($request) {
                    return $request->ask_type === false;
                 }),
                'sometimes',
                'integer'],
            'rating' => 'integer',
            'min_stock' => [
                Rule::RequiredIf(function () use ($request) {
                    return $request->ask_type === false;
                 }),
                'sometimes',
                'integer'],
            'categories' => 'required_if:ask_price,===,false',
            'discount'  => 'sometimes|numeric',
            'tags' => 'sometimes|max:255',
            'product_variants' => ''
        ]);
    

        //required_if:sale_type,==,Unidad|required_if:ask_price,==,false
        $validator->sometimes('price', 'required', function($request){
            return ($request->sale_type == 'Unidad' && !$request->ask_price == true);
        });

        $validator->sometimes('product_variants', 'required', function($request){
            return ($request->sale_type == 'Peso' && !$request->ask_price == true);
        });

        $validator->validate();

        if($request->is_week_combo === 1){
            $weekCombo = Product::where('is_week_combo',1)->first();
            if($weekCombo->count())
            $weekCombo->active = 0;
            $weekCombo->save();
        }   

        $product->update($request->all());

        $product->categories()->detach();
        foreach($request->categories as $category){
            $product->categories()->attach($category['id']);
        }
         
         
         if($product->sale_type === 'Peso'){
             foreach($request->product_variants as $variant){
                logger($variant);
                 ProductVariant::updateOrCreate([
                     'id' => $variant['id']
                 ],[
                    'product_id' => $product->id,
                    'weight' => $variant['weight'],
                    'price' => $variant['price'],
                    'active' => $variant['active']
                 ]);
             }
         }    

        return response()->json($product, 200);
    }

    public function storeImage(Request $request){
        
        $this->validate($request, [
            'id' => 'required',
            'files' => 'required',
        ]);
        
        if($request->has('files')){
            $i = 1;
            foreach($request->file('files') as  $file){
                
                $resize = Image::make($file)->widen(512,function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode($file->getClientOriginalExtension());
                
                $imagename =md5(preg_replace('/\s+/', '', time().$file->getClientOriginalName()));
                
                
                Storage::disk('productos')->put($imagename,  $resize->__toString());

               
                // $file[0]->storeAs('public/images',$imagename);
                $image = new ProductImage();
                $image->name = $imagename;
                $image->order = $i;
                $image->product_id = $request->id;
                $image->save();
                $i++;
            }
        }
        return response()->json(true);
    }

    public function sortImages(Request $request){

        foreach($request->images as $key => $image){
            $image = ProductImage::find($image['id']);
            $image->order = $key;
            $image->save();
        }

        return response()->json(true);

    }

    public function deleteImage(Request $request){
        $image = ProductImage::find($request->id);
        Storage::disk('productos')->delete($image->name);
        $image->delete();
        return response()->json(true);
        
    }

    public function latest(){
        $products = Product::orderBy('id','desc')->active()->take(12)->get();
        return response()->json($products);
    }

    public function ExportProductList(){
        return \Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function ImportProductList(Request $request){
      
        $this->validate($request, [
            'file' => 'required',
        ]);
        \Excel::import(new ProductsImports, $request->file('file'));
        
        return  response()->json(true);
    }

}
