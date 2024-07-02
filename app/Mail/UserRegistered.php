<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Mailable
{   
    public $link;
    use Queueable, SerializesModels;

  
    public function __construct($link)
    {
        $this->link = $link;
    }

  
    public function build()
    {
        return $this->from('no-reply@ecomarketonline.com.ar')
                    ->subject('Gracias por registrarse en '. config('app.name') )
                    ->markdown('emails.user-registered');
    }
}
