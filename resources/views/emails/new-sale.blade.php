@component('mail::message')

<img src="{{ asset('/img/banner-email.png') }}" alt="logo"  data-auto-embed="attachment">

<span style="text-align: center;margin-top:40px;margin-bottom:40px;">
Tenemos un nuevo pedido!<br>
</span>  

De: {{$data->name}} <br>
Email: {{ $data->email}} <br>
Teléfono: {{ $data->phone }} <br>
Nota: {{ $data->order_note }}
Dirección: {{ $data->delivery_address}}

<table style="width:100%;margin-top:40px;margin-bottom:40px;">
  <thead>
    <tr>  
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Descuento</th>
      <th>Total</th>
    </tr>
  </thead>
  
  <tbody>
  @foreach($data->items as $item)
  <tr>
    <td>{{ $item->variant_id ? $item->variant_description : $item->description }}</td>
    <td>{{ $item->qty }}</td>
    <td>%{{ $item->discount }}</td>
    <td> ${{ $item->total }}</td>
  </tr>
  @endforeach
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
    <td><strong>${{ number_format((float)$data->total, 2, '.', '') }}</strong></td>
  </tr>
  </tbody>
 

 
</table>


Gracias,<br>
{{ config('app.name') }}
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