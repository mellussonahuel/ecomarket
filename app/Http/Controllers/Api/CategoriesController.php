<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use App\Models\Category;
use Intervention\Image\Facades\Image;
use App\Models\ProductImage;
use Storage;

class CategoriesController extends ResourceController
{
    protected $resourceModel = 'App\Models\Category';
    protected $paginate = false;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function afterIndex($query)
    {
        return $query->orderBy('order','asc')->with('parent');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'active' => '',
            'description' => 'required|string|max:255',
        ]);

        $category = Category::create(
            $request->all()
        );
      
        return response()->json($category,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    

    public function getCategories(){
      
        $query =  Category::query();
        $query->with('parent');
        $query->with(['childrens' => function($query){
            $query->where('active',1);
        }]);
        $query->active();
        $query->withCount(['products' => function($query){
            $query->where('active',1);
        }]);

        $query->orderBy('order','asc');
        $query->active();
        $categories = $query->get();
        
        return response()->json($categories);
    }
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'active' => '',
            'description' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return response()->json($category, 200);
    }

    public function storeImage(Request $request){
        
        $this->validate($request, [
            'id' => 'required',
            'files' => 'required',
        ]);

       
        
        if($request->has('files')){
            $image = ProductImage::where('category_id',$request->id)->first();
            if($image){
                Storage::disk('categories')->delete($image->name);
                $image->delete();
            }
            
            $i = 1;
            foreach($request->file('files') as  $file){
                
                $resize = Image::make($file)->widen(512,function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode($file->getClientOriginalExtension());
                
                $imagename = md5(time()).trim($file->getClientOriginalName());

                Storage::disk('categories')->put($imagename,  $resize->__toString());

               
                // $file[0]->storeAs('public/images',$imagename);
                $image = new ProductImage();
                $image->name = $imagename;
                $image->order = $i;
                $image->category_id = $request->id;
                $image->save();
                $i++;
            }
        }

        return response()->json(true);
    }

    public function getCategoryData($slug){
        $category = Category::where('slug',$slug)->with('image')->first();
        return response()->json($category);
    }

    public function deleteImage(Request $request){
        $image = ProductImage::find($request->id);
        Storage::disk('categories')->delete($image->name);
        $image->delete();
        return response()->json(true);
        
    }

    public function sort(Request $request){

        foreach($request->categories as $key => $category){
            $category = Category::find($category['id']);
            $category->order = $key;
            $category->save();
        }

        return response()->json(true);

    }
}
