@extends('layouts.quotationclient')

@section('content')
    <div>

        {{-- ================= ENCABEZADO ================= --}}
        <table class="oc-header-table">
            <tr>
                <td width="18%">
                    @if ($company->url != '')
                        <img width="110" src="{{ public_path('storage' . $company->url) }}">
                    @else
                        <img width="110" src="{{ public_path('img/logosupra.png') }}">
                    @endif
                </td>
                <td width="42%">
                    <p class="oc-company-name">{{ $company->razonSocial }}</p>
                    <p class="oc-company-giro">{{ $company->giro }}</p>
                    <p class="oc-company-giro">RUT: {{ $company->rut }}</p>
                    <p class="oc-company-giro">Dirección: {{ $company->address }}</p>
                    <p class="oc-company-giro">Email: {{ $company->email }}</p>
                    <p class="oc-company-giro">Cel: {{ $company->phone }}</p>
                    <p class="oc-company-giro">Vendedor: {{ $user->name }}</p>
                </td>
                <td width="40%">
                    <p class="oc-title">COTIZACIÓN</p>
                    <p class="oc-number">
                        @if ($quotation->user_id === 1)
                            N° {{ $quotation->id }}
                        @else
                            N° {{ $quotation->correlativo }}{{ $quotation->correlativo_suffix ? '.' . $quotation->correlativo_suffix : '' }}
                        @endif
                    </p>
                    <p class="oc-meta">FECHA: {{ $quotation->created_at->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>

        <hr class="oc-hr">

        {{-- ================= CLIENTE / VEHICULO ================= --}}
        @php $hasVehicle = $quotation->ppu != '' || $quotation->vehicle != ''; @endphp
        <table class="oc-info-table" style="margin-top: 8px;">
            <tr>
                <td @if (!$hasVehicle) colspan="2" @endif>
                    <div class="oc-info-box">
                        <p class="oc-info-title">Datos del Cliente</p>
                        @if ($client->type == 'Cliente Particular')
                            <table style="width: auto;">
                                <tr>
                                    <td class="oc-info-label">Cliente:</td>
                                    <td class="oc-info-value">{{ $quotation->client_text }}</td>
                                </tr>
                            </table>
                        @elseif ($hasVehicle)
                            <table style="width: auto;">
                                <tr>
                                    <td class="oc-info-label">Cliente:</td>
                                    <td class="oc-info-value">{{ $client->name }}</td>
                                </tr>
                                <tr>
                                    <td class="oc-info-label">Empresa:</td>
                                    <td class="oc-info-value">{{ $client->razonSocial }}</td>
                                </tr>
                                <tr>
                                    <td class="oc-info-label">RUT:</td>
                                    <td class="oc-info-value">{{ $client->rut }}</td>
                                </tr>
                                <tr>
                                    <td class="oc-info-label">Dirección:</td>
                                    <td class="oc-info-value">{{ $client->address }}</td>
                                </tr>
                                <tr>
                                    <td class="oc-info-label">Ciudad:</td>
                                    <td class="oc-info-value">{{ $client->comuna }}</td>
                                </tr>
                                <tr>
                                    <td class="oc-info-label">Teléfono:</td>
                                    <td class="oc-info-value">
                                        <table>
                                            <tr>
                                                <td class="oc-info-value oc-info-value-inline">{{ $client->phone }}</td>
                                                <td class="oc-info-label-inline">Correo:</td>
                                                <td class="oc-info-value">{{ $client->email }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        @else
                            <table class="oc-info-2col">
                                <tr>
                                    <td class="oc-info-2col-left">
                                        <table style="width: auto;">
                                            <tr>
                                                <td class="oc-info-label">Cliente:</td>
                                                <td class="oc-info-value">{{ $client->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="oc-info-label">Empresa:</td>
                                                <td class="oc-info-value">{{ $client->razonSocial }}</td>
                                            </tr>
                                            <tr>
                                                <td class="oc-info-label">RUT:</td>
                                                <td class="oc-info-value">{{ $client->rut }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="oc-info-2col-right">
                                        <table style="width: auto;">
                                            <tr>
                                                <td class="oc-info-label">Dirección:</td>
                                                <td class="oc-info-value">{{ $client->address }}</td>
                                            </tr>
                                            <tr>
                                                <td class="oc-info-label">Ciudad:</td>
                                                <td class="oc-info-value">{{ $client->comuna }}</td>
                                            </tr>
                                            <tr>
                                                <td class="oc-info-label">Teléfono:</td>
                                                <td class="oc-info-value">{{ $client->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td class="oc-info-label-inline">Correo:</td>
                                                <td class="oc-info-value">{{ $client->email }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        @endif
                    </div>
                </td>
                @if ($hasVehicle)
                    <td>
                        <div class="oc-info-box">
                            <p class="oc-info-title">Datos del Vehículo</p>
                            <table style="width: auto;">
                                @if ($quotation->vehicle != '')
                                    <tr>
                                        <td class="oc-info-label">Vehículo:</td>
                                        <td class="oc-info-value">{{ $quotation->vehicle }}</td>
                                    </tr>
                                @endif
                                @if ($quotation->ppu != '')
                                    <tr>
                                        <td class="oc-info-label-wide">PPU/N° Interno:</td>
                                        <td class="oc-info-value">{{ $quotation->ppu }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </td>
                @endif
            </tr>
        </table>

        <p class="oc-lead">Tenemos el agrado de cotizar a ustedes, lo siguiente:</p>

        {{-- ================= PRODUCTOS ================= --}}
        <table class="oc-products">
            <thead>
                <tr>
                    <th class="oc-col-qty">Cant</th>
                    <th class="oc-col-desc">Descripción</th>
                    @if ($quotation->show_part_number)
                        <th class="oc-col-part">N° Parte</th>
                    @endif
                    <th class="oc-col-days">Plazo Entrega</th>
                    <th class="oc-col-price">Valor Unitario</th>
                    <th class="oc-col-total">Valor Total</th>
                </tr>
            </thead>
            <tbody>

                <?php $contador = 0; ?>
                <?php $totalServicio = 0; ?>
                @foreach ($products as $detail)
                    <?php $valorUnitario = ceil($detail->total / $detail->quantity / 1.19 / 10) * 10; ?>
                    <?php $valorTotal = $valorUnitario * $detail->quantity; ?>
                    <tr>
                        <td class="oc-col-qty">{{ $detail->quantity }}</td>
                        <td class="oc-col-desc">{{ $detail->product }}</td>
                        @if ($quotation->show_part_number)
                            <td class="oc-col-part">{{ $detail->detail }}</td>
                        @endif
                        <td class="oc-col-days">{{ $detail->days }}</td>
                        @if ($detail->quantity > 0)
                            <td class="oc-col-price">$ {{ number_format($valorUnitario, 0, ',', '.') }}</td>
                        @else
                            <td class="oc-col-price">$0</td>
                        @endif
                        <td class="oc-col-total">$ {{ number_format($valorTotal, 0, ',', '.') }}</td>
                        <?php $totalServicio += $valorTotal; ?>
                        <?php $contador = $contador + 1; ?>
                    </tr>
                @endforeach

            </tbody>
        </table>

        {{-- ================= OBSERVACIONES / TOTALES ================= --}}
        <table class="oc-bottom-table">
            <tr>
                <td class="oc-obs-col">
                    <div class="oc-obs-box">
                        <p class="oc-obs-title">Observaciones</p>
                        <p>Condiciones de Pago: {{ $quotation->payment }}</p>
                        <p>Validez cotización: 5 días</p>
                    </div>
                </td>
                <td class="oc-totals-col">
                    <table class="oc-totals-table">
                        <tr>
                            <td class="oc-totals-label">Neto</td>
                            <td class="oc-totals-value">$ {{ number_format($totalServicio, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="oc-totals-label">IVA</td>
                            <td class="oc-totals-value">$ {{ number_format(ceil(($totalServicio * 0.19) / 10) * 10, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="oc-total-row">
                            <td class="oc-totals-label">TOTAL</td>
                            <td class="oc-totals-value">$ {{ number_format(ceil(($totalServicio * 1.19) / 10) * 10, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- ================= REPUESTOS SOLICITADOS (texto libre) ================= --}}
        @if ($quotation->spare_parts !== '')
            <div class="oc-spare-box">
                <p class="oc-obs-title">Repuestos Solicitados</p>
                <p>{!! nl2br(e($quotation->spare_parts)) !!}</p>
            </div>
        @endif

        {{-- ================= IMAGENES DE PRODUCTOS ================= --}}
        @php
            $productsWithImages = $products->filter(fn($detail) => $detail->images->count() > 0);
        @endphp
        @if ($productsWithImages->count() > 0)
            <div style="page-break-before: always;">
                <p class="oc-images-title">Imágenes de Productos</p>
                @foreach ($productsWithImages as $detail)
                    <p class="oc-images-product">{{ $detail->product }}</p>
                    @foreach ($detail->images as $image)
                        <img src="{{ public_path($image->imagen) }}" style="max-width: 300px; margin: 5px;">
                    @endforeach
                @endforeach
            </div>
        @endif

        {{-- ================= REPUESTOS SOLICITADOS (tabla estructurada con fotos) ================= --}}
        @if ($spareParts->count() > 0)
            <div style="page-break-before: always;">
                <p class="oc-images-title">Repuestos Solicitados</p>
                <table class="oc-products" style="margin-top: 6px;">
                    <thead>
                        <tr>
                            <th class="oc-col-qty">Cant</th>
                            <th class="oc-col-desc">Producto</th>
                            <th class="oc-col-code">Código</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($spareParts as $part)
                            <tr>
                                <td class="oc-col-qty">{{ $part->quantity }}</td>
                                <td class="oc-col-desc">{{ $part->product }}</td>
                                <td class="oc-col-code">{{ $part->detail }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @foreach ($spareParts as $part)
                    @if ($part->images->count() > 0)
                        <p class="oc-images-product">{{ $part->product }}</p>
                        @foreach ($part->images as $image)
                            <img src="{{ public_path($image->imagen) }}" style="max-width: 300px; margin: 5px;">
                        @endforeach
                    @endif
                @endforeach
            </div>
        @endif

        {{-- ================= BARRA INFERIOR ================= --}}
        <div class="oc-footer-bar">
            {{ $company->phone }} &nbsp;|&nbsp; {{ $company->email }}
        </div>

    </div>
@endsection
