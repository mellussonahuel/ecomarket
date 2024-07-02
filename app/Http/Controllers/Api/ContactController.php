<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  Illuminate\Support\Facades\Mail;
use App\Mail\WebMessage;

class ContactController extends Controller
{
    public function send(Request $request){

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'phone' => 'string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

  
        Mail::to(config('mail.to.hola'))->send(new WebMessage($request->all()));
        
        return response()->json(!Mail::failures());

    }
}
