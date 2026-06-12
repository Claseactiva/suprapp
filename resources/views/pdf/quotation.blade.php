@extends('layouts.quotation')

@section('content')

    <table class="table" style="border:none;">
        <thead>
        </thead>
        <tbody>
            <tr>
                <td style="padding-top:25px;border:none;">
                    <img width="220" height="100"
                     src="http://comercialsupra.cl/wp-content/uploads/2019/05/logosupra-copia-2.jpg">
                </td>
                <td class="text-center"  style="padding-top:40px;border:none;">
                    <span style="font-size:24px">Cotización N°{{ $quotation->id }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px;margin-bottom:20px">
        <span style="font-size:16px">Cliente: {{ $quotation->client }}</span>,
        <span style="font-size:16px">Vehículo: {{ $quotation->vehicle }}</span>,
        <span style="font-size:16px">Patente: {{ $quotation->patent }}</span>
    </div>

    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>N°</th>
                <th>Detalle</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <?php $totalServicio = 0; ?>
            <?php $contador = 1 ?>
            @foreach($products as $detail)
            <tr>
                <td width="30px"><?php echo $contador ?></td>
                <td>{{ $detail->product }}</td>
                <td width="150px">{{ $detail->price }}</td>
                <?php $totalServicio += $detail->price ?>
                <?php $contador += 1 ?>
            </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td>NETO: {{ $totalServicio }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>IVA: {{ $totalServicio * 0.19 }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>TOTAL: {{ $totalServicio * 1.19 }}</td>
            </tr>
        </tbody>
    </table>


    <table class="table table-bordered">
        <thead>
        </thead>
        <tbody>
            <tr>
                <td class="text-left">
                    <div style="font-size:16px">
                       Correo: comercialsupra4@gmail.com
                    </div>
                    <div style="font-size:16px">
                       Whatsapp: +56 9 8948 3379
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

@endsection
