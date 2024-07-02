@component('mail::message')
<img src="{{ asset('/img/result.png') }}" alt="logo"  data-auto-embed="attachment">

<span style="display:block;text-align: center;margin-top:100px;margin-bottom:40px;">
<p style="width: 100%;text-align:center;font-size:25px">¡Gracias por elegirnos! <br> Te informamos que el pago de tu compra ha sido acreditado!.</p>
</span>

<br>
<p style="padding: 40px;">
El pedido lo entregaremos en el domicilio que registraste para ese fin y en la fecha que previamente elegiste. <br>
Por cualquier duda podés comunicarte con nosotras enviando un WhatsApp 
<a target="_blanck" href="https://api.whatsapp.com/send?phone=+5492235764153" >(+54 9 2235 - 764153)</a>
con tu número de pedido y responderemos tus consultas. <br>
Sigamos junt@s el camino del consumo consciente. <br><br>
</p>

<table style="width:100%;margin-top:40px;margin-bottom:40px;">
  <tbody
    <tr>
      <td>
        Gracias,<br>
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