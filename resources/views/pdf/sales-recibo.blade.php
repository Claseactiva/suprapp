@extends('layouts.layoutsale')

@section('content')
    <table>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td colspan="2" style="border: 0px; padding-bottom: 10px;"><b>{{ $sale['client']->razonSocial }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="border: 0px;">Recibo</td>

                    <td style="border: 0px;">{{ $sale->id }}</td>
                </tr>

                <tr>
                    <td style="border: 0px;">Direccion</td>
                    <td style="border: 0px;">{{ $sale['client']->address }}</td>
                </tr>

                <tr>
                    <td style="border: 0px;">Telefono</td>

                    <td style="border: 0px;">{{ $sale['client']->phone }}</td>
                </tr>

                <tr>
                    <td style="border: 0px;">Fecha</td>
                    <td style="border: 0px;">{{ $sale->created_at }}</td>
                </tr>

                <tr>
                    <td style="border: 0px;">Empleado</td>
                    <td style="border: 0px;">{{ $sale['user']->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr style="margin-bottom: 5px; margin-top: 5px;">

    <table>
        <tbody>
            <?php $totalServicio = 0; ?>
            @foreach ($sales[0]['productSales'] as $productSale)
                <tr>
                    <td style="border: 0px;" colspan="3">{{ $productSale['product']->name }}</td>
                </tr>
                <tr>
                    @if ($sales[0]->descuento > 0)
                        <td style="border: 0px;">{{ $productSale->quantity }} X ${{ ($productSale->total / $productSale->quantity) -  (($productSale->total / $productSale->quantity) * $sales[0]->descuento)}}</td>
                    @else
                        <td style="border: 0px;">{{ $productSale->quantity }} X ${{ $productSale->total / $productSale->quantity }}
                        </td>
                    @endif
                    <td style="border: 0px;" width="50px"></td>
                    @if ($sales[0]->descuento > 0)
                        <td style="border: 0px;" width="50px">${{ $productSale->total - ($productSale->total * $sales[0]->descuento) }}</td>
                    @else
                        <td style="border: 0px;" width="50px">${{ $productSale->total }}</td>
                    @endif
                </tr>
                <?php $totalServicio += $productSale->total; ?>
            @endforeach
        </tbody>
    </table>

    <hr style="margin-bottom: 5px; margin-top: 5px;">

    <table>
        <tbody>
            <tr>
                <th style="border: 0px;">TOTAL</th>
                <td style="border: 0px;" width="50px"></td>
                @if ($sales[0]->descuento > 0)
                    <th style="border: 0px;" width="50px">
                        ${{ number_format($totalServicio * $sales[0]->descuento, 0, ',', '.') }}
                    </th>
                @else
                    <th style="border: 0px;" width="50px">${{ number_format($totalServicio, 0, ',', '.') }}</th>
                @endif
            </tr>
        </tbody>
    </table>
@endsection
