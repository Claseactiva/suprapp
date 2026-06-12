@extends('layouts.quotationclient')

@section('content')
    <div>
        <table>
            <tbody>
                <tr>

                    <td>
                        @if ($company->url != '')
                            <img width="150" src="{{ 'storage' . $company->url }}">
                        @else
                            <img width="150" src="img/logosupra.png">
                        @endif
                    </td>


                    <td colspan="8" class="title-quotation">
                        <p class="bold">{{ $company->razonSocial }}</p>
                        <p>{{ $company->giro }}</p>
                    </td>

                    <td colspan="2" class="border">
                        <p>RUT: {{ $company->rut }}</p>
                        <p>COTIZACIÓN</p>
                        @if ($quotation->user_id === 1)
                            <p>{{ $quotation->id }}</p>
                        @else
                            <p>{{ $quotation->correlativo }}</p>
                        @endif
                        <p>FECHA: {{ $quotation->created_at->format('d/m/Y') }}</p>
                    </td>
                </tr>

                <tr>
                    <td class="separate"></td>
                </tr>
                <tr>
                    <td colspan="12" class="border-light padding font-size">
                        Direccion: {{ $company->address }} - Email: {{ $company->email }} - Cel: {{ $company->phone }} -
                        Vendedor: {{ $user->name }}
                    </td>
                </tr>
                <tr>
                    <td class="separate-2"></td>
                </tr>

                @if ($client->type == 'Cliente Particular')
                    <tr>
                        <td class="text-left font-size">Cliente:</td>
                        <td colspan="12" class="text-left bold background font-size">{{ $quotation->client_text }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="text-left font-size">Cliente:</td>
                        <td colspan="12" class="text-left bold background font-size">{{ $client->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-size">Empresa:</td>
                        <td colspan="12" class="text-left bold background font-size">{{ $client->razonSocial }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-size">RUT:</td>
                        <td colspan="12" class="text-left bold background font-size">{{ $client->rut }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-size">Dirección:</td>
                        <td colspan="12" class="text-left bold background font-size">{{ $client->address }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-size">Ciudad:</td>
                        <td colspan="12" class="text-left bold background font-size">{{ $client->comuna }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-size">Email:</td>
                        <td colspan="12" class="text-left bold background font-size">{{ $client->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-size">Teléfono:</td>
                        <td colspan="12" class="text-left bold background font-size">{{ $client->phone }}</td>
                    </tr>
                @endif
                @if ($quotation->ppu != '')
                    <tr>
                        <td class="text-left font-size">PPU/N°Interno:</td>
                        <td colspan="12" class="text-left bold background font-size">{{ $quotation->ppu }}</td>
                    </tr>
                @endif
                @if ($quotation->vehicle != '')
                    <tr>
                        <td class="text-left font-size">Vehículo:</td>
                        <td colspan="12" class="text-left bold background font-size">{{ $quotation->vehicle }}</td>
                    </tr>
                @endif

                <tr>
                    <td class="padding"></td>
                </tr>
                <tr>
                    <td colspan="12" class="bold">Tenemos el agrado de cotizar a ustedes, lo siguiente:</td>
                </tr>
                <tr>
                    <td class="padding"></td>
                </tr>
            </tbody>
        </table>

        <table class="product">
            <thead>
                <tr>
                    <th>Cant</th>
                    <th>Descripción</th>
                    <th>Plazo Entrega</th>
                    <th>Valor Unitario</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>

                <?php $contador = 0; ?>
                <?php $totalServicio = 0; ?>
                @foreach ($products as $detail)
                    <?php $valorUnitario = ceil($detail->total / $detail->quantity / 10) * 10; ?>
                    <?php $valorTotal = $valorUnitario * $detail->quantity; ?>
                    <tr>
                        <td class="cell">{{ $detail->quantity }}</td>
                        <td>{{ $detail->product }}</td>
                        <td class="cell">{{ $detail->days }}</td>
                        @if ($detail->quantity > 0)
                            <td class="cell">$ {{ $valorUnitario }}</td>
                        @else
                            <td class="cell">$0</td>
                        @endif
                        <td class="cell">$ {{ $valorTotal }}</td>
                        <?php $totalServicio += $valorTotal; ?>
                        <?php $contador = $contador + 1; ?>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="text-right bold font-size">Total: $ {{ ceil($totalServicio / 10) * 10 }}</p>

        <p class="bold">Observaciones</p>
        <p>Condiciones de Pago: {{ $quotation->payment }}</p>
        <p>Validez cotización: 5 días</p>


        @if ($quotation->spare_parts !== '')
            </br>
            <p class="bold">Repuestos Solicitados</p>
            <p>{!! nl2br(e($quotation->spare_parts)) !!}</p>
        @endif

    </div>
@endsection
