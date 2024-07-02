@component('mail::message')
<img class="banner" src="{{ asset('/img/result.png') }}" alt="logo"  data-auto-embed="attachment">

<span style="display:block;text-align: center;margin-top:100px;margin-bottom:40px;">
<p style="width: 100%;text-align:center;font-size:25px">¡Gracias por elegirnos!</p>
</span>

<br>
<p style="padding: 40px; text-align:center">
Si recibiste este email , ya estas registrado en EcoMarket! <br><br> 
Suscribite al newsletter  <a style="display:inline-block" href="{{ $link }}" target="_blank">acá</a>  y recibí novedades, info útil y promos! </br></br>


</p>

<p style="text-align: center;">
<a style="display:inline-block;margin-top:15px;position:relative;width:230px" href="{{ url('home') }}" target="_blank"> Te esperamos por la web <img  style="position:absolute;right:0px" width="30" src="{{url('img/green-cart.png')}}" alt="cart"></a> 
</p>

<table style="width:100%;margin-top:40px;margin-bottom:40px;">
  <tbody
    <tr>
      <td>
        Saludos,<br>
        {{ config('app.name') }}
      </td>
      <td >
        <span style="display:inline-block">
          <a style="display:inline-block" href="https://www.facebook.com/ecomarketonlinemdq" target="_blank"><img  width="30" src="{{url('img/facebook.png')}}" alt="facebook"></a>
          <a style="display:inline-block" href="https://www.instagram.com/ecomarketonline_/?hl=es-la" target="_blank"><img width="30" src="{{url('img/instagram.png')}}" alt="instagram"></a>
        </span>
      </td>
    </tr>
  </tbody>
</table>

<style>
    table th,td{
        text-align: center;
        margin-bottom: 40px;
    }

    .banner{
        display: block;
        margin: 0 auto;
        margin-top: 40px;
        margin-bottom: 40px;
    }

    .purchase-total-description{
        text-align: right;
    }
</style>