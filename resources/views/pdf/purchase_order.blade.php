@extends('layouts.purchase_order')

@php
    $currencySymbols = [
        'PESO CHILENO' => '$',
        'DOLAR' => 'US$',
        'EURO' => '€',
    ];
    $currencySymbol = $currencySymbols[$order->currency] ?? '$';
    $isPesoChileno = ($order->currency ?? 'PESO CHILENO') === 'PESO CHILENO';
@endphp

@section('content')
    <div>

        {{-- ================= ENCABEZADO ================= --}}
        <table class="oc-header-table">
            <tr>
                <td width="18%">
                    @if ($company->url != '')
                        <img width="110" src="{{ 'storage' . $company->url }}">
                    @else
                        <img width="110" src="img/logosupra.png">
                    @endif
                </td>
                <td width="42%">
                    <p class="oc-company-name">{{ $company->razonSocial }}</p>
                    <p class="oc-company-giro">{{ $company->giro }}</p>
                </td>
                <td width="40%">
                    <p class="oc-title">ORDEN DE COMPRA</p>
                    <p class="oc-number">
                        N° {{ $order->order_number ?: ('OC-' . str_pad($order->user_id === 1 ? $order->id : $order->correlativo, 4, '0', STR_PAD_LEFT)) }}
                    </p>
                    <p class="oc-meta">FECHA: {{ $order->created_at->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>

        <hr class="oc-hr">

        {{-- ================= PROVEEDOR / ENTREGA ================= --}}
        <table class="oc-info-table">
            <tr>
                <td>
                    <div class="oc-info-box">
                        <p class="oc-info-title">Datos del Proveedor</p>
                        <table>
                            @if ($supplier)
                                <tr>
                                    <td class="oc-info-label">Nombre:</td>
                                    <td class="oc-info-value">{{ $supplier->razonSocial }}</td>
                                </tr>
                                <tr>
                                    <td class="oc-info-label">RUT:</td>
                                    <td class="oc-info-value">{{ $supplier->rut }}</td>
                                </tr>
                                @if ($supplier->giro)
                                    <tr>
                                        <td class="oc-info-label">Giro:</td>
                                        <td class="oc-info-value">{{ $supplier->giro }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="oc-info-label">Dirección:</td>
                                    <td class="oc-info-value">{{ $supplier->address }}</td>
                                </tr>
                                <tr>
                                    <td class="oc-info-label">Ciudad:</td>
                                    <td class="oc-info-value">{{ $supplier->comuna }}</td>
                                </tr>
                                @if ($supplier->contacto)
                                    <tr>
                                        <td class="oc-info-label">Contacto:</td>
                                        <td class="oc-info-value">{{ $supplier->contacto }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="oc-info-label">Teléfono:</td>
                                    <td class="oc-info-value">{{ $supplier->phone }}</td>
                                </tr>
                                @if ($supplier->email)
                                    <tr>
                                        <td class="oc-info-label">Correo:</td>
                                        <td class="oc-info-value">{{ $supplier->email }}</td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td class="oc-info-label">Nombre:</td>
                                    <td class="oc-info-value">{{ $order->supplier_text }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </td>
                <td>
                    <div class="oc-info-box">
                        <p class="oc-info-title">Datos de Cliente</p>
                        <table>
                            <tr>
                                <td class="oc-info-label">Empresa:</td>
                                <td class="oc-info-value">{{ $company->razonSocial }}</td>
                            </tr>
                            <tr>
                                <td class="oc-info-label">RUT:</td>
                                <td class="oc-info-value">{{ $company->rut }}</td>
                            </tr>
                            <tr>
                                <td class="oc-info-label">Dirección:</td>
                                <td class="oc-info-value">{{ $company->address }}</td>
                            </tr>
                            <tr>
                                <td class="oc-info-label">Ciudad:</td>
                                <td class="oc-info-value">{{ $company->comuna }}</td>
                            </tr>
                            <tr>
                                <td class="oc-info-label">Contacto:</td>
                                <td class="oc-info-value">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td class="oc-info-label">Teléfono:</td>
                                <td class="oc-info-value">
                                    <table>
                                        <tr>
                                            <td class="oc-info-value oc-info-value-inline">{{ $company->phone }}</td>
                                            <td class="oc-info-label">Correo:</td>
                                            <td class="oc-info-value">{{ $company->email }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            @if ($order->ship_to)
                                <tr>
                                    <td class="oc-info-label">Envío a:</td>
                                    <td class="oc-info-value">{{ $order->ship_to }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        {{-- ================= METODO ENVIO / FORMA DE PAGO / COMPRADOR / SOLICITADO POR ================= --}}
        @if ($order->shipping_method || $order->payment || $order->buyer || $order->requested_by)
            <table class="oc-extra-row">
                <tr>
                    @if ($order->shipping_method)
                        <td class="oc-extra-label">Método de Envío:</td>
                        <td class="oc-extra-value">{{ $order->shipping_method }}</td>
                    @endif
                    @if ($order->payment)
                        <td class="oc-extra-label">Forma de Pago:</td>
                        <td class="oc-extra-value">{{ $order->payment }}</td>
                    @endif
                    @if ($order->buyer)
                        <td class="oc-extra-label">Comprador:</td>
                        <td class="oc-extra-value">{{ $order->buyer }}</td>
                    @endif
                    @if ($order->requested_by)
                        <td class="oc-extra-label">Solicitado Por:</td>
                        <td class="oc-extra-value">{{ $order->requested_by }}</td>
                    @endif
                    <td></td>
                </tr>
            </table>
        @endif

        <p class="oc-lead">Tenemos el agrado de solicitar a ustedes, el siguiente pedido:</p>

        {{-- ================= PRODUCTOS ================= --}}
        <table class="oc-products">
            <thead>
                <tr>
                    <th class="oc-col-num">N°</th>
                    <th class="oc-col-code">Código</th>
                    <th class="oc-col-desc">Descripción</th>
                    <th class="oc-col-qty">Cant.</th>
                    <th class="oc-col-days">Plazo Entrega</th>
                    <th class="oc-col-price">Precio Unit.</th>
                    <th class="oc-col-total">Total</th>
                </tr>
            </thead>
            <tbody>

                <?php $totalPedido = 0; ?>
                @foreach ($products as $index => $detail)
                    <tr>
                        <td class="oc-col-num">{{ $index + 1 }}</td>
                        <td class="oc-col-code">{{ $detail->detail }}</td>
                        <td class="oc-col-desc">{{ $detail->product }}</td>
                        <td class="oc-col-qty">{{ $detail->quantity }}</td>
                        <td class="oc-col-days">{{ $detail->days }}</td>
                        <td class="oc-col-price">{{ $currencySymbol }} {{ number_format($detail->price, 2, ',', '.') }}</td>
                        <td class="oc-col-total">{{ $currencySymbol }} {{ number_format($detail->total, 2, ',', '.') }}</td>
                        <?php $totalPedido += $detail->total; ?>
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
                        @if ($order->observaciones)
                            <p>{!! nl2br(e($order->observaciones)) !!}</p>
                        @endif
                        <p>Validez orden de compra: 5 días</p>
                    </div>
                </td>
                <td class="oc-totals-col">
                    <table class="oc-totals-table">
                        <?php
                            $ivaPedido = $isPesoChileno ? (ceil(($totalPedido * 0.19) / 10) * 10) : round($totalPedido * 0.19, 2);
                            $totalConIva = $isPesoChileno ? (ceil(($totalPedido * 1.19) / 10) * 10) : round($totalPedido * 1.19, 2);
                        ?>
                        <tr>
                            <td class="oc-totals-label">Neto</td>
                            <td class="oc-totals-value">{{ $currencySymbol }} {{ number_format($totalPedido, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="oc-totals-label">IVA</td>
                            <td class="oc-totals-value">{{ $currencySymbol }} {{ number_format($ivaPedido, 2, ',', '.') }}</td>
                        </tr>
                        <tr class="oc-total-row">
                            <td class="oc-totals-label">TOTAL</td>
                            <td class="oc-totals-value">{{ $currencySymbol }} {{ number_format($totalConIva, 2, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

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
                        <img src="{{ $image->imagen }}" style="max-width: 300px; margin: 5px;">
                    @endforeach
                @endforeach
            </div>
        @endif

        {{-- ================= BARRA INFERIOR ================= --}}
        <div class="oc-footer-bar">
            {{ $company->phone }} &nbsp;|&nbsp; {{ $company->email }}
        </div>

    </div>
@endsection
