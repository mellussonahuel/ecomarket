<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSale extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    use Queueable, SerializesModels;

  
    public function __construct($data)
    {
        $this->data = $data;
    }

    
    public function build()
    {
        return $this->from('no-reply@ecomarketonline.com.ar')
        ->subject('Nueva venta '. config('app.name') )
        ->markdown('emails.new-sale');
    }
}
