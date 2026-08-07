@extends('layouts.layoutshipping')

@section('content')
    <table>   
        <tbody>
            @foreach($shippings as $shipping)
                @if($shipping->id)
                <tr class="cliente">
                    <th>ID:</th>
                    <td>{{ $shipping->id }}</td>
                </tr>
                @endif
                @if($shipping->nombre)
                <tr class="cliente">
                    <th>ATTE:</th>
                    <td>{{ $shipping->nombre }}</td>
                </tr>
                @endif
                @if($shipping->rut)
                <tr class="cliente">
                    <th>RUT:</th>
                    <td>{{ $shipping->rut }}</td>
                </tr>
                @endif
                @if($shipping->telefono)
                <tr class="cliente">
                    <th>CEL:</th>
                    <td>{{ $shipping->telefono }}</td>
                </tr>
                @endif
                @if($shipping->ciudad)
                <tr class="cliente">
                    <th>CIUDAD:</th>
                    <td>{{ $shipping->ciudad }}</td>
                </tr>
                @endif
                @if($shipping->direccion)
                <tr class="cliente">
                    <th>DIRECCION:</th>
                    <td>{{ $shipping->direccion }}</td>
                </tr>
                @endif
                @if($shipping->sucursal)
                <tr class="cliente">
                    <th>SUCURSAL:</th>
                    <td>{{ $shipping->sucursal }}</td>
                </tr>
                @endif
            @endforeach
                <tr>
                    <th style="padding-top: 15px;">RTE:</th>    
                    <td style="padding-top: 15px;">COMERCIAL SUPRA E.I.R.L</td>
                </tr>
                <tr>
                    <th>RUT:</th> 
                    <td>76.515.046-9</td>
                </tr>
                <tr>
                    <th>CEL:</th>
                    <td>+56 9 8948 3379</td>
                </tr>
                <tr>
                    <th>DIRECCION:</th>
                    <td>Av. Rubén Jiménez 601, Coquimbo</td>
                </tr>
        </tbody>
    </table>
@endsection


