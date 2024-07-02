<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use App\User;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;

class UsersController extends ResourceController
{
    protected $resourceModel = 'App\User';
    protected $paginate = true;
    protected $perPage = 12;

    protected function afterIndex($query)
    {
        return $query;
    }

    protected function byTerm($query, $term)
    {

        $words = explode(' ', $term);

        return $query->where(function ($query) use ($words) {
            // $query->where('title','LIKE', "$term->label%");

            foreach ($words as $word) {

                $query->where('name', 'LIKE', "$word%")
                    ->orWhere('email', 'LIKE', "$word%");


                // $query->orWhereHas('categories', function ($query) use ($word) {
                //     $query->where('description', 'LIKE', "$word%");
                // });    
            }

            return $query;
        });
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $data = $request->all();

        return Validator::make($request, [
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'address' => 'max:255',
            'birth_date' => 'required|date',
            'location_id' => 'required',
            'email' => 'required|email|max:255|unique:users',
        ]);

        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'birth_date' => $data['birth_date'],
            'location_id' => $data['location_id'],
            'address' => $data['address'],
            'password' => bcrypt('eco_mk_123'),
        ]);

        return response()->json($user);
    }

    public function filter(Request $request)
    {


        $term = $request->term;

        $query = User::query();

        if ($term) {
            $words = explode(' ', $term);

            $query->where(function ($query) use ($words) {
                // $query->where('title','LIKE', "$term->label%");

                foreach ($words as $word) {

                    $query->where('name', 'LIKE', "$word%")
                        ->orWhere('email', 'LIKE', "$word%");


                    // $query->orWhereHas('categories', function ($query) use ($word) {
                    //     $query->where('description', 'LIKE', "$word%");
                    // });    
                }

                return $query;
            });
        }

        $query->skip(intval($request->page) * intval($request->perPage ?? $this->perPage))->paginate(intval($request->perPage ?? $this->perPage));

        $users = $query->paginate();

        return response()->json($users, 200);
    }

    public function edit(Request $request)
    {
    }

    public function update(Request $request, User $user)
    {

        $data = $request->all();

        $this->validate($request, [
            'name' => 'required|max:255',
            'phone' => 'max:255',
            'address' => 'max:255',
            'birth_date' => 'required|date',
            'location_id' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->id . ',id',
        ]);


        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->birth_date = $request->birth_date;
        $user->location_id = $request->location_id;
        $user->email = $request->email;
        $user->save();

        return response()->json($user);
    }
}
