<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;

class UserController extends Controller
{
    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function current(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request){
        $data = $request->all();

        $this->validate($request, [
            'name' => 'required|max:255',
            'phone' => 'max:255|required',
            'address' => 'max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$data['id'],
        ]);
        
        $user = User::find($request->id);
        
        $user->update($data); 
        
        return response()->json($user);
    }

    public function setFavorites(Request $request){
        
        
        $userFavs =      auth()
                        ->user()
                        ->products()
                        ->pluck('products.id');
                        
        $favorites = null;
        if($request->favorites){
            $favorites = collect(explode(',',$request->favorites));
        }
       
        if($favorites){
            // merge user saved favorites
            $allFavorites = $favorites->merge($userFavs)->unique(); 
            auth()->user()->products()->sync($allFavorites);
            // detach favoritess and set new if its has
            // auth()->user()->products()->detach();
            // foreach($allFavorites as $favorite){
            //     auth()->user()->products()->attach($favorite);
            // }
        }
        
        
        return response()->json(
            auth()
            ->user()
            ->products()
            ->pluck('products.id')
            ->toArray()
        );

    }

    public function getFavorites(){
        return response()->json(
            auth()
            ->user()
            ->products()->get()
        );
    }

    public function removeFavorite($product_id){
        
        auth()->user()->products()->detach($product_id);
        
        return response()->json(
            auth()
            ->user()
            ->products()->get()
        );

    }
}
