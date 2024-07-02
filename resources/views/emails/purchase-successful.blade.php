@component('mail::message')

<img src="{{ asset('/img/banner-email.png') }}" alt="logo"  data-auto-embed="attachment">

<span style="text-align: center;margin-top:40px;margin-bottom:40px;">
¡Gracias por elegirnos!<br>
Te enviamos el detalle de tu pedido.
</span>    

<table style="width:100%;margin-top:40px;margin-bottom:40px;">
  <thead>
    <tr>  
      <th>Descripción</th>
      <th>Cantidad</th>
      <th>Precio</th>
      <th>Descuento</th>
      <th>Total</th>
    </tr>
  </thead>
  
  <tbody>
  @foreach($data->items as $item)
 
  <tr>
    <td>{{ $item->variant_id ? $item->variant_description : $item->description }}</td>
    <td>{{ $item->qty }}</td>
    <td>${{ $item->price }}</td>
    <td>%{{ $item->discount }}</td>
    <td> ${{ $item->total }}</td>
  </tr>
  @endforeach
  @if($data->coupon_code != '')
  <tr class="total">
    <td class="purchase-total-description" colspan="4"><strong>Cupón aplicado ({{ $data->coupon_code}})</strong></td>
    <td><strong>%{{ $data->coupon_amount }}</strong></td>
  </tr>
  @endif
  <tr class="subtotal">
    <td class="purchase-total-description" colspan="4"><strong>Subtotal</strong></td>
    <td><strong>${{  number_format((float)$data->subtotal, 2, '.', '')  }}</strong></td>
  </tr>
  @if($data->total_discount != 0)
  <tr class="total">
    <td class="purchase-total-description" colspan="4"><strong>Total descuentos</strong></td>
    <td><strong>${{  number_format((float)$data->total_discount, 2, '.', '')  }}</strong></td>
  </tr>
  @endif
  @if($data->total < 700)
  <tr class="total">
    <td class="purchase-total-description" colspan="4"><strong>Diferencia por compra mínima</strong></td>
    <td><strong>${{  number_format((float) 700 - $data->total , 2, '.', '')  }}</strong></td>
  </tr>
  @endif
  <tr class="total">
    <td class="purchase-total-description" colspan="4"><strong>Total de tu compra</strong></td>
    <td><strong>${{ $data->total < 700 ? 700 :  number_format((float)$data->total, 2, '.', '') }}</strong></td>
  </tr>
  </tbody>
 

 
</table>

@if($data->payment_type === 'WIRE_TRANSFER' )

Elegiste la opción pago mediante transferencia, vamos a pedirte que realices la transferencia a esta cuenta:
  <ul>
  <li><strong>Titular:</strong> Mariana Barovero Maxit</li>
  <li><strong>CBU:</strong>0140323503420065793614</li>
  <li><strong>Alias:</strong>CANCHA.CUEVA.FUTURO</li>
  <li><strong>Cuil:</strong>27-34500378-4</li>
  </ul>
 
Y luego nos envíes el comprobante por <a target="_blanck" href="https://api.whatsapp.com/send?phone=+5492235764153" >whatsapp</a> o al mail <a href="mailto:hola@ecomarketonline.com.ar"> hola@ecomarketonline.com.ar </a>  para poder chequearlo! Una vez chequeado nos contactaremos con vos para enviarte tú pedido!


@endif



El pedido lo entregaremos en el domicilio que registraste para ese fin, las entregas se realizan los días Lunes, Miercoles y Viernes. <br>
El envío se podrá realizar en el mismo día si el pedido ingresa antes de las 12 hs); 
caso contrario pasan al día siguiente (excepto en el caso de los sábados y domingos que se realizan el día lunes o los 
feriados que se trasladan al siguiente día hábil).<br>
Por cualquier duda podés comunicarte con nosotras enviando un WhatsApp 
<a target="_blanck" href="https://api.whatsapp.com/send?phone=+5492235764153" >(+54 9 2235 - 764153)</a>
con tu número de pedido y responderemos tus consultas. <br>
Sigamos junt@s el camino del consumo consciente. <br><br>

Número de pedido: {{ now()->format('dY').$data->id }}

@if($data->payment_type === 'MP' )
Si no pudiste completar el pago por Mercado Pago hace click <a href="{{ $data->mpLink }}"> acá</a> 
@endif
<table style="width:100%;margin-top:40px;margin-bottom:40px;">
  <tbody>
    <tr>
      <td>
        Gracias,<br>
        {{ config('app.name') }}
      </td>
      <td>
        <span style="display:inline-block">
          <a style="display:inline-block" href="https://www.facebook.com/ecomarketonlinemdq" target="_blank"><img  width="30" src="{{url('img/facebook.png')}}" alt="facebook"></a>
          <a style="display:inline-block" href="https://www.instagram.com/ecomarketonline_/?hl=es-la" target="_blank"><img width="30" src="{{url('img/instagram.png')}}" alt="instagram"></a>
        </span>
      </td>
    </tr>
  </tbody>
</table>

@endcomponent


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