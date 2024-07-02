@component('mail::message')

<img src="{{ asset('/img/banner-email.png') }}" alt="logo"  data-auto-embed="attachment">

<span style="text-align: center;margin-top:40px;margin-bottom:40px;">
¡Tenemos un regalo para vos!<br>
</span>    

código : {{ $coupon->code }}




Gracias,<br>
{{ config('app.name') }}
@endcomponent


<style>
    img{
        display: block;
        margin: 0 auto;
        margin-top: 40px;
        margin-bottom: 40px;
    }

    .purchase-total-description{
        text-align: right;
    }
</style>