<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseSuccessful extends Mailable
{   
    public $data;
    use Queueable, SerializesModels;

  
    public function __construct($data)
    {
        $this->data = $data;
    }

  
    public function build()
    {
        return $this->from('no-reply@ecomarketonline.com.ar')
                    ->subject('Gracias por comprar en '. config('app.name') )
                    ->markdown('emails.purchase-successful');
    }
}
