<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class ResourceController extends Controller
{
    protected $id;
    protected $translatedMessages;
    protected $authenticated;
    protected $resourceModel;

    public function __construct(Request $request)
    {
        $this->authenticated = $request->user();
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
       
        $query = $this->resourceModel::query();
        # load relationships
        $relationships = [];
        if ($request->has('load')) {
            $relationships = $this->resourceModel::$loadable;

            $loadRelationship = explode(',', $request->load);
            if ($loadRelationship[0] != 'all') {
                foreach ($relationships as $index => $relationship) {
                    if (!in_array($relationship, $loadRelationship)) {
                        unset($relationships[$index]);
                    }
                }
            }
        }
        $query = $query->with($relationships);


        # load sorting
        if (method_exists($this->resourceModel, 'sort')) {
            $sortField = $request->get('sort');
            $sortType = $request->get('sortType');
            $query = $this->resourceModel::sort($query, $sortField, $sortType);
        }


        # load filtering
        if (method_exists($this->resourceModel, 'filter')) {
            $filters = [];
            #if ($request->has('filters')) {
            #    $filters = $this->convertParamIfJSON($request->filters);
            #}
            #$query = $this->resourceModel::filter($query, $filters);
        }

        # Search
        //only with laravel scout
//        $searchText = $request->get('search');
//        if ($searchText) {
//            $search_ids = $this->resourceModel::search($searchText)->keys()->toArray();
//            $query = $query->whereIn('id', $search_ids);
//        }

        # load extra logic from child class
        if (method_exists($this, 'afterIndex')) {
            $query = $this->afterIndex($query);
        }

        
        if (method_exists($this, 'byTerm') && $request->term != '') {
            $query = $this->byTerm($query,$request->term);
           
        }

        if($request->active){
            $query->where('active',1);
        }
        # Paginate and response
        
        if ((isset($this->paginate) && $this->paginate) || $request->perPage != 'false') {
            $result = $query->skip($request->page * $request->perPage)->paginate(intval($request->perPage));
        } else {
            $result = [ 'data' => $query->get() ];
        }

        return response()->json($result, 200);
    }

    public function show(Request $request,$id){
        $query = $this->resourceModel::query();
        $query->where('id',$id);
        # load relationships
        $relationships = [];
      
        if ($request->has('load')) {
            $relationships = $this->resourceModel::$loadable;

            $loadRelationship = explode(',', $request->load);
            if ($loadRelationship[0] != 'all') {
                foreach ($relationships as $index => $relationship) {
                    if (!in_array($relationship, $loadRelationship)) {
                        unset($relationships[$index]);
                    }
                }
            }
        }
        $query = $query->with($relationships);

        $item = $query->first();
        if($item){
            return response()->json($item, 200);
        }

        return response()->json('Not found', 404);
    }

    public function destroy(Request $request, $id)
    {
        $this->id = (int) $id;

        if (method_exists($this, 'beforeDestroy') && !$this->beforeDestroy($request, $id)) {
            return response()->json(['error' => 'Unable to delete'], 500);
        }

        $resource = $this->resourceModel::destroy($id);
        if ($resource) {
            if (isset($this->afterDestroy) && is_array($this->afterDestroy)) {
                foreach ($this->afterDestroy as $method) {
                    $this->$method($id);
                }
            }
            return response()->json(null, 204);
        } else {
            return response()->json('Unable to delete', 400);
        }
    }


    protected function filteredLoad($load, $userId)
    {
        $result = [];
        if (isset($this->resourceModel::$filteredLoadable)) {
            if (is_array($load)) {
                foreach ($load as $loadValue) {
                    if (isset($this->resourceModel::$filteredLoadable[$loadValue])) {
                        $key = $this->resourceModel::$filteredLoadable[$loadValue];
                        $result[$loadValue] = function ($query) use ($userId, $key) {
                            $query->where($key, '=', $userId);
                        };
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param mixed $param
     *
     * @return array
     */
    protected function convertParamIfJSON($param)
    {
        $result = [];
        if (is_array($param)) {
            $result = $param;
        } else {
            $param = json_decode($param, 1);
            if (!empty($param)) {
                $result = $param;
            }
        }
        return $result;
    }


//    protected function authorization($permission)
//    {
//        if ($this->authenticated->hasPermissionTo($permission)) {
//            abort(401);
//        }
//    }
}
