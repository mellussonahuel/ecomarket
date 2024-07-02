<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use App\Models\Location;

class LocationsController extends ResourceController
{
   
    protected $resourceModel = 'App\Models\Location';
    protected $paginate = false;
    protected $perPage= 12;

    protected function afterIndex($query)
    {   
        return $query;
    }

   
    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|max:255',
            'min' => 'required'
        ]);

        $location = Location::create(
            $request->all()
        );

        return response()->json($location);
    }

 

    
    public function edit($id)
    {
        //
    }

    public function update(Request $request, Location  $location)
    {
        $this->validate($request, [
            'description' => 'required|max:255',
            'min' => 'required'
        ]);

        $location = $location->update(
            $request->all()
        );

        return response()->json($location);
    }

}
