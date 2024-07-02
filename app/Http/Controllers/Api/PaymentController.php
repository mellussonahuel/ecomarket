<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Sale;
use MercadoPago;
use Illuminate\Support\Facades\Http;
use App\Mail\PaymentStatus;
use Mail;

class PaymentController extends Controller
{

    public function __construct()
    {
        MercadoPago\SDK::setAccessToken(config('mp.credentials.token'));
    }

    public function generarPago($hash)
    {
        // dd(route('payment.mp.response'));
        // "http://test.ecomarketonline.com.ar/api/payments/mp-response"
        $sale_id = Crypt::decryptString($hash);
        $sale = Sale::find($sale_id);

        // creo la preferencia de pago
        $preference = new MercadoPago\Preference();

        // información del comprador
        $payer = new MercadoPago\Payer();
        $payer->name = $sale->user->name;
        $payer->email = $sale->user->email;
        $payer->date_created = now();
        $payer->phone = array(
            "number" => $sale->user->phone
        );
        $payer->address = array(
            "street_name" => $sale->delivery_address,
        );

        $preference->payer = $payer;
        $totalitems = [];
        
        // Crea un ítem en la preferencia
        foreach ($sale->items as $it) {

            $item = new MercadoPago\Item();

            $item->id = $it->item_id;
            $item->title = $it->product->title;
            $item->description = $it->product->description;
            $item->category_id = "home";
            $item->quantity = $it->qty;
            $item->currency_id = "ARS";
            $item->unit_price = $it->total;

            array_push($totalitems, $item);
        }

        $preference->items = $totalitems;

        $preference->back_urls = array(
            "success" => url('api/payments/resultado-pago'),
            "failure" => url('api/payments/resultado-pago'),
            "pending" => url('api/payments/resultado-pago')
        );
        $preference->payment_methods = array(
            "installments" => 1,
        );

        $preference->excluded_payment_types = ["id" => "ticket"]; 

        $preference->auto_return = "approved";
        $preference->external_reference = $sale->id;
        $preference->save();

        return view('payment', compact('preference'));
    }

    private function storePayment($payment_id = null, $status, $external_reference, $merchant_order_id)
    {
        
        $payment = Payment::find($payment_id);
         
        if(!$payment){
            $payment = new Payment();
        }
        
        $payment->mp_payment_id = $payment_id;
        $payment->external_reference = $external_reference;
        $payment->status = $status;
        $payment->merchant_order_id = $merchant_order_id;
        $payment->save();

        return $payment->id;
    }
    // http://localhost:8000/api/resultado-pago?collection_id=1232786943&
    // collection_status=approved
    // &payment_id=1232786943&
    // status=approved
    // &external_reference=8
    // &payment_type=credit_card
    // &merchant_order_id=2215276598
    // &preference_id=698650268-993be1b6-0297-4196-af2f-be60524f313a&site_id=MLA&processing_mode=aggregator&merchant_account_id=null

    public function mpResponse(Request $request)
    {
        // dd($request->collection_id);
        $sale = Sale::find($request->external_reference);
        $payment_id = $this->storePayment($request->payment_id, $request->status, $request->external_reference, $request->merchant_order_id);
        $sale->payment_status = $request->status === 'approved' ? 'APPROVED' : 'PENDING';
        $sale->payment_id = $payment_id;
        $sale->save();
        if($request->status === 'approved'){
            $this->sendPaymentStatusNotification($sale->email);
        }

        return redirect('/resultado/' . $request->status);
    }

    public function mpIpnResponse(Request $request)
    {

        $response = Http::withToken(config('mp.credentials.token'))
             ->get('https://api.mercadolibre.com/merchant_orders/2203646328');
            //->get('https://api.mercadolibre.com/merchant_orders/' . $request->id);

        $data = json_decode($response->getBody());
        
        if(!$data->error && $data->status === 'closed'){
            $payment_id = $this->storePayment($data->payment_id, $data->status, $data->external_reference, $data->merchant_order_id);
            $sale = Sale::find($data->external_reference);
            $sale->payment_status = 'APPROVED';
            $sale->payment_id = $payment_id;
            $sale->save();
            $this->sendPaymentStatusNotification($sale->email);
        }
        
        $status = "200";
        $status_header = 'HTTP/1.0 ' . $status . ' OK';
        header($status_header);
        exit;
    }

    public function sendPaymentStatusNotification($email){
        Mail::to($email)->send(new PaymentStatus());
    
        if (Mail::failures()) {
            return false;
        }

        return true;
    }
}

