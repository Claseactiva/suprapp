@extends('layouts.layoutshipping')

@section('content')
    <table>   
        <tbody>
            @foreach($shippings as $shipping)
                <tr>
                    <th>ID:</th>    
                    <td>{{ $shipping->id }}</td>
                </tr>
                <tr>
                    <th>ATTE:</th>    
                    <td>{{ $shipping->nombre }}</td>
                </tr>
                <tr>
                    <th>RUT:</th> 
                    <td>{{ $shipping->rut }}</td>
                </tr>
                <tr>
                    <th>CEL:</th>
                    <td>{{ $shipping->telefono }}</td>
                </tr>
                <tr>
                    <th>CIUDAD:</th>
                    <td>{{ $shipping->ciudad }}</td>
                </tr>
                <tr>
                    <th>DIRECCION:</th>
                    <td>{{ $shipping->direccion }}</td>
                </tr>
                <tr>
                    <th>SUCURSAL:</th>
                    <td>{{ $shipping->sucursal }}</td>
                </tr>
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


