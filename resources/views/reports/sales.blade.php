<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<style>
    /* @font-face {
        font-family: 'Open Sans';
        font-style: normal;
        font-weight: normal;
        src: url(http://themes.googleusercontent.com/static/fonts/opensans/v8/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf) format('truetype');
    } */

    .page-break {
        page-break-after: always;
    }

    body {
        font-family: "Open Sans", Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
    }

    table {
        border-left: 0.01em solid #ccc;
        border-right: 0;
        border-top: 0.01em solid #ccc;
        border-bottom: 0;
        border-collapse: collapse;
    }

    table td,
    table th {
        border-left: 0;
        border-right: 0.01em solid #ccc;
        border-top: 0;
        border-bottom: 0.01em solid #ccc;
        padding: 5px;
    }

    thead {
        background-color: #ebe9e4;
    }

    th {
        padding: 5px;
        border: 1px solid #abaaa9;
    }

    .products-table tbody td {
        font-size: 12px;
        /* width: 200px; */
        text-align: center;
    }

    tbody tr {
        padding-top: 5px;
        padding-bottom: 5px;
        border: 1px solid #abaaa9;
    }

    .item-container {
        border-top: 1px dotted #000000;
        margin-top: 40px;
        margin-bottom: 40px;
        padding-top: 40px;
        padding-bottom: 40px;
    }

    .products-table {
        width: 100%;
        margin-top: 30px;
    }

    .table-title {
        margin-bottom: 10px;
        margin-top: 10px;
    }
</style>

<body>
    @if(isset($filters))
        @if(isset($filters['from']))
        <div>
            Desde : {{ $filters['from']}}
        </div>
        @endif

        @if(isset($filters['to']))
        <div>
            Hasta : {{ $filters['to']}}
        </div>
        @endif
        
        @if(isset($filters['location']))
        <div>
            Hasta : {{ $filters['location']}}
        </div>
        @endif
    @endif
    
    @forelse($sales as $sale)

    <div class="item-container">
        <div class="table-title">Datos del cluente:</div>
        <table class="">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Fecha pedido</th>
                    <th>Fecha entrega</th>
                    <th>Dirección entrega</th>
                    <th>Tipo de pago</th>
                    <th>Estado pago</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $sale->name }}</td>
                    <td>{{ $sale->phone }}</td>
                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$sale->created_at)->format('d/m/Y') }}</td>
                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d',$sale->delivery_date)->format('d/m/Y') }}</td>
                    <td>{{ $sale->delivery_address }}</td>
                    <td>{{ Config::get('constants.payment_types')[$sale->payment_type] ?? ''}}</td>
                    <td>{{ Config::get('constants.payment_status')[$sale->payment_status] ?? ''}}</td>
                </tr>
            </tbody>
        </table>
        <div class="table-title">Productos:</div>
        <table class="products-table">
            <thead>
                <tr>
                    <th>producto</th>
                    <th>cantidad</th>
                    <th>Total cantidad</th>
                    <th>Total pesos</th>
                </tr>
            </thead>
            <tbody>
        
                @forelse($sale->items as $item)
              
                <tr>
                    <td>  {{ $item->variant_id ?  $item->variant_description : $item->description }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{  $item->variant_id ? $item->variant_total :  $item->qty }}</td>
                    <td>${{ $item->total }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">
                        Sin productos
                    </td>
                </tr>
                @endforelse
                
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td colspan="1" style="text-align: center;">
                        <strong>${{ $sale->total }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>

    
    @if( count($sale->items) > 4)
    <div class="page-break"></div>
    @endif
    @empty
        <p style="margin-top: 100px;text-align:center;width:100%"> <strong>Sin datos</strong>  </p>
    @endforelse
    
</body>

</html>