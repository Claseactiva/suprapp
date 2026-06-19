@extends('layouts.layoutcajaZ')

@section('content')
    <table>
        <tbody>
            <tr>
                <td colspan="2" style="border: 0px; padding-bottom: 10px;"><b>{{ $sales[0]['client']->razonSocial }}</b>
                </td>
            </tr>
            <tr>
                <td style="border: 0px;">Recibo</td>

                <td style="border: 0px;">{{ $sales[0]->id }}</td>
            </tr>

            <tr>
                <td style="border: 0px;">Direccion</td>
                <td style="border: 0px;">{{ $sales[0]['client']->address }}</td>
            </tr>

            <tr>
                <td style="border: 0px;">Telefono</td>

                <td style="border: 0px;">{{ $sales[0]['client']->phone }}</td>
            </tr>

            <tr>
                <td style="border: 0px;">Fecha</td>
                <td style="border: 0px;">{{ $sales[0]->created_at }}</td>
            </tr>

            <tr>
                <td style="border: 0px;">Empleado</td>
                <td style="border: 0px;">{{ $sales[0]['user']->name }}</td>
            </tr>
        </tbody>
    </table>
    <hr>

    <table>
        <tbody>
            <?php $totalServicio = 0; ?>
            @foreach ($sales as $sale)
                @foreach ($sale['productSales'] as $productSale)
                    <tr>
                        <td style="border: 0px;" colspan="2">{{ $productSale['product']->name }}</td>
                    </tr>
                    <tr>
                        <td style="border: 0px;" width="600px">{{ $productSale->quantity }} X
                            @if ($sales[0]->descuento > 0)
                                ${{ $productSale->total / $productSale->quantity - ($productSale->total / $productSale->quantity) * $sales[0]->descuento }}
                            @else
                                ${{ $productSale->total / $productSale->quantity }}
                            @endif
                        </td>
                        <td style="border: 0px;">
                            @if ($sales[0]->descuento > 0)
                                ${{ $productSale->total - $productSale->total * $sales[0]->descuento }}
                                <?php $totalServicio += $productSale->total - $productSale->total * $sales[0]->descuento; ?>
                            @else
                                ${{ $productSale->total }}
                                <?php $totalServicio += $productSale->total; ?>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <hr>

    <table>
        <tbody>
            <tr>
                <th style="border: 0px;" width="600px">TOTAL</th>
                <th style="border: 0px;">${{ number_format($totalServicio, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>
@endsection
