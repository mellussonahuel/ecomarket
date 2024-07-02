<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;
use App\Http\Controllers\ResourceController;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class NewsletterController extends ResourceController
{
   
    protected $resourceModel = 'App\Models\Newsletter';
    protected $paginate = false;
    protected $perPage= 12;

   
    protected function afterIndex($query)
    {   
        return $query;
    }

   
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users',
        ]);

        $news = Newsletter::Updatecreate(
            $request->all()
        );

        return response()->json($news);
    }

    public function storeExternal(Request $request){
        
        $email = null;
        try {
            $email = Crypt::decryptString($request->email);
        } catch (DecryptException $e) {
            //
        }

        $news = null;

        if($email)
        $news = Newsletter::UpdateOrCreate(
            ['email' => $email]
        );

        return response()->json(true);
    }

    public function remove($email){
        $news = Newsletter::where('email',$email)->delete();
        return response()->json($news);
    }

  
    

    
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

  
  
}
