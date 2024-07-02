<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use App\Mail\CouponGenerated;
use App\User;
use App\Models\Coupon;
use Mail;
use Auth;
use Carbon\Carbon;

class CouponsController  extends ResourceController
{
   
    protected $resourceModel = 'App\Models\Coupon';
    protected $paginate = false;
    protected $perPage= 12;
   
    
    protected function afterIndex($query)
    {   
        return $query;
    }

    public function searchUser($email){
        
        $user = User::where('email',$email)->first();

        return response()->json($user);
    }

    public function generateCode(){
        return response([
            'CODE' => 'success',
            'data' => Coupon::getNewCouponCode(),
        ], 200);
    }

    

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'sometimes|nullable|required_if:all_users,false|email|max:191',
            'amount' => 'required|integer|between:1,100',
            'code' => 'required',
            'type' => 'required'
        ]);
            
        if($request->all_users === true){
            $data = $request->all();
             
            User::active()->get()->map(function($u) use ($data){
                $data['email'] =  $u->email;
                Coupon::create($data);
            });
        }else{
            $coupon = Coupon::create(
                $request->all()
            );
        }    

        // if($request->send_email){
        //     $this->sendCode($coupon);
        // }

        return response()->json(true);
    }

    private function sendCode($coupon){
        
        Mail::to($coupon->email)->send(new CouponGenerated($coupon));
        
        if (Mail::failures()) {
            return false;
        }

        return true;
    }

   
    public function update(Request $request,Coupon $coupon)
    {
        $this->validate($request, [
            'email' => 'required|email|max:191|unique:coupons,email,'.$coupon->id,
            'amount' => 'required|integer|between:1,10',
            'code' => 'required',
            'type' => 'required'
        ]);
            
       
        $coupon->update(
            $request->all()
        );

        if($request->send_email){
            $this->sendCode($coupon);
        }

        return response()->json($coupon);
    }

    public function validateCode(Request $request){

        $this->validate($request, [
            'code' => 'required',
        ]);
            
        $coupon = Coupon::where('email',Auth::user()->email)
                        ->where('code',strtolower($request->code))
                        ->first();
                        
        if(!$coupon){
            return response()->json(['status' => false , 'msg' => '¡Ooops!, El cupón ingresado no es válido.' ]);
        }
                        
        if($coupon){
            if($coupon->used === 1 && $coupon->type === 'ONE_USE' ){
                return response()->json(['status' => false , 'msg' => '¡Ooops!, El cupón ya fue utilizado.'  ]);
            }
       
            if(Carbon::now()->gt($coupon->valid_to)){
                return response()->json(['status' => false , 'msg' => '¡Ooops!, este cupón ha expirado. ']);
            }
      
            if($coupon->valid_from->gt(Carbon::now())){
                return response()->json(['status' => false , 'msg' => '¡Ooops!, Este cupón aún no se encuentra habilitado. ']);
            }
        }

        
        
                
        return response()->json(['status' => true , 'coupon' => $coupon ]);
    
    }



}
