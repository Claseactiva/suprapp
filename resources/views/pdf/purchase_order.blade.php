@extends('layouts.purchase_order')

@php
    $currencySymbols = [
        'PESO CHILENO' => '$',
        'DOLAR' => 'US$',
        'EURO' => '€',
    ];
    $currencySymbol = $currencySymbols[$order->currency] ?? '$';
    $isPesoChileno = ($order->currency ?? 'PESO CHILENO') === 'PESO CHILENO';

    $formatAmount = function ($value) {
        $value = (float) $value;
        if (abs($value - round($value, 2)) >= 0.00005) {
            $decimals = 4;
        } elseif (abs($value - round($value)) >= 0.005) {
            $decimals = 2;
        } else {
            $decimals = 0;
        }
        return number_format($value, $decimals, ',', '.');
    };
    $formatCurrency = function ($value) use ($currencySymbol, $formatAmount) {
        return $currencySymbol . '&nbsp;' . $formatAmount($value);
    };

    $lang = $lang ?? 'es';
    $t = [
        'es' => [
            'title' => 'ORDEN DE COMPRA',
            'date' => 'FECHA',
            'supplier_info' => 'Datos del Proveedor',
            'customer_info' => 'Datos de Cliente',
            'name' => 'Nombre',
            'rut' => 'RUT',
            'business' => 'Giro',
            'address' => 'Dirección',
            'city' => 'Ciudad',
            'contact' => 'Contacto',
            'phone' => 'Teléfono',
            'email' => 'Correo',
            'company' => 'Empresa',
            'ship_to' => 'Envío a',
            'shipping_method' => 'Método de Envío',
            'payment_method' => 'Forma de Pago',
            'buyer' => 'Comprador',
            'requested_by' => 'Solicitado Por',
            'lead' => 'Tenemos el agrado de solicitar a ustedes, el siguiente pedido:',
            'col_num' => 'N°',
            'col_code' => 'Código',
            'col_desc' => 'Descripción',
            'col_qty' => 'Cant.',
            'col_days' => 'Plazo Entrega',
            'col_price' => 'Precio Unit.',
            'col_total' => 'Total',
            'notes' => 'Observaciones',
            'validity' => 'Validez orden de compra: 5 días',
            'net' => 'Neto',
            'vat' => 'IVA',
            'total' => 'TOTAL',
            'images' => 'Imágenes de Productos',
        ],
        'en' => [
            'title' => 'PURCHASE ORDER',
            'date' => 'DATE',
            'supplier_info' => 'Supplier Information',
            'customer_info' => 'Customer Information',
            'name' => 'Name',
            'rut' => 'RUT',
            'business' => 'Business Activity',
            'address' => 'Address',
            'city' => 'City',
            'contact' => 'Contact',
            'phone' => 'Phone',
            'email' => 'Email',
            'company' => 'Company',
            'ship_to' => 'Ship to',
            'shipping_method' => 'Shipping Method',
            'payment_method' => 'Payment Method',
            'buyer' => 'Buyer',
            'requested_by' => 'Requested By',
            'lead' => 'We are pleased to place the following order:',
            'col_num' => 'No.',
            'col_code' => 'Code',
            'col_desc' => 'Description',
            'col_qty' => 'Qty.',
            'col_days' => 'Delivery Time',
            'col_price' => 'Unit Price',
            'col_total' => 'Total',
            'notes' => 'Notes',
            'validity' => 'Purchase order valid for 5 days',
            'net' => 'Subtotal',
            'vat' => 'VAT',
            'total' => 'TOTAL',
            'images' => 'Product Images',
        ],
    ][$lang];
@endphp

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
                </td>
                <td width="40%">
                    <p class="oc-title">{{ $t['title'] }}</p>
                    <p class="oc-number">
                        N° {{ $order->order_number ?: ('OC-' . str_pad($order->user_id === 1 ? $order->id : $order->correlativo, 4, '0', STR_PAD_LEFT)) }}
                    </p>
                    <p class="oc-meta">{{ $t['date'] }}: {{ $order->created_at->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>

        <hr class="oc-hr">

        {{-- ================= PROVEEDOR / ENTREGA ================= --}}
        <table class="oc-info-table">
            <tr>
                <td>
                    <div class="oc-info-box">
                        <p class="oc-info-title">{{ $t['supplier_info'] }}</p>
                        <table>
                            @if ($supplier)
                                <tr>
                                    <td class="oc-info-label">{{ $t['name'] }}:</td>
                                    <td class="oc-info-value">{{ $supplier->razonSocial }}</td>
                                </tr>
                                <tr>
                                    <td class="oc-info-label">{{ $t['rut'] }}:</td>
                                    <td class="oc-info-value">{{ $supplier->rut }}</td>
                                </tr>
                                @if ($supplier->giro)
                                    <tr>
                                        <td class="oc-info-label">{{ $t['business'] }}:</td>
                                        <td class="oc-info-value">{{ $supplier->giro }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="oc-info-label">{{ $t['address'] }}:</td>
                                    <td class="oc-info-value">{{ $supplier->address }}</td>
                                </tr>
                                <tr>
                                    <td class="oc-info-label">{{ $t['city'] }}:</td>
                                    <td class="oc-info-value">{{ $supplier->comuna }}</td>
                                </tr>
                                @if ($supplier->contacto)
                                    <tr>
                                        <td class="oc-info-label">{{ $t['contact'] }}:</td>
                                        <td class="oc-info-value">{{ $supplier->contacto }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="oc-info-label">{{ $t['phone'] }}:</td>
                                    <td class="oc-info-value">{{ $supplier->phone }}</td>
                                </tr>
                                @if ($supplier->email)
                                    <tr>
                                        <td class="oc-info-label">{{ $t['email'] }}:</td>
                                        <td class="oc-info-value">{{ $supplier->email }}</td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td class="oc-info-label">{{ $t['name'] }}:</td>
                                    <td class="oc-info-value">{{ $order->supplier_text }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </td>
                <td>
                    <div class="oc-info-box">
                        <p class="oc-info-title">{{ $t['customer_info'] }}</p>
                        <table>
                            <tr>
                                <td class="oc-info-label">{{ $t['company'] }}:</td>
                                <td class="oc-info-value">{{ $company->razonSocial }}</td>
                            </tr>
                            <tr>
                                <td class="oc-info-label">{{ $t['rut'] }}:</td>
                                <td class="oc-info-value">{{ $company->rut }}</td>
                            </tr>
                            <tr>
                                <td class="oc-info-label">{{ $t['address'] }}:</td>
                                <td class="oc-info-value">{{ $company->address }}</td>
                            </tr>
                            <tr>
                                <td class="oc-info-label">{{ $t['city'] }}:</td>
                                <td class="oc-info-value">{{ $company->comuna }}</td>
                            </tr>
                            <tr>
                                <td class="oc-info-label">{{ $t['contact'] }}:</td>
                                <td class="oc-info-value">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td class="oc-info-label">{{ $t['phone'] }}:</td>
                                <td class="oc-info-value">
                                    <table>
                                        <tr>
                                            <td class="oc-info-value oc-info-value-inline">{{ $company->phone }}</td>
                                            <td class="oc-info-label">{{ $t['email'] }}:</td>
                                            <td class="oc-info-value">{{ $company->email }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            @if ($order->ship_to)
                                <tr>
                                    <td class="oc-info-label">{{ $t['ship_to'] }}:</td>
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
                        <td class="oc-extra-label">{{ $t['shipping_method'] }}:</td>
                        <td class="oc-extra-value">{{ $order->shipping_method }}</td>
                    @endif
                    @if ($order->payment)
                        <td class="oc-extra-label">{{ $t['payment_method'] }}:</td>
                        <td class="oc-extra-value">{{ $order->payment }}</td>
                    @endif
                    @if ($order->buyer)
                        <td class="oc-extra-label">{{ $t['buyer'] }}:</td>
                        <td class="oc-extra-value">{{ $order->buyer }}</td>
                    @endif
                    @if ($order->requested_by)
                        <td class="oc-extra-label">{{ $t['requested_by'] }}:</td>
                        <td class="oc-extra-value">{{ $order->requested_by }}</td>
                    @endif
                    <td></td>
                </tr>
            </table>
        @endif

        <p class="oc-lead">{{ $t['lead'] }}</p>

        {{-- ================= PRODUCTOS ================= --}}
        <table class="oc-products">
            <thead>
                <tr>
                    <th class="oc-col-num">{{ $t['col_num'] }}</th>
                    <th class="oc-col-code">{{ $t['col_code'] }}</th>
                    <th class="oc-col-desc">{{ $t['col_desc'] }}</th>
                    <th class="oc-col-qty">{{ $t['col_qty'] }}</th>
                    <th class="oc-col-days">{{ $t['col_days'] }}</th>
                    <th class="oc-col-price">{{ $t['col_price'] }}</th>
                    <th class="oc-col-total">{{ $t['col_total'] }}</th>
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
                        <td class="oc-col-price">{!! $formatCurrency($detail->price) !!}</td>
                        <td class="oc-col-total">{!! $formatCurrency($detail->total) !!}</td>
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
                        <p class="oc-obs-title">{{ $t['notes'] }}</p>
                        @if ($order->observaciones)
                            <p>{!! nl2br(e($order->observaciones)) !!}</p>
                        @endif
                        <p>{{ $t['validity'] }}</p>
                    </div>
                </td>
                <td class="oc-totals-col">
                    <table class="oc-totals-table">
                        <?php
                            $ivaPedido = $isPesoChileno ? (ceil(($totalPedido * 0.19) / 10) * 10) : round($totalPedido * 0.19, 2);
                            $totalConIva = $isPesoChileno ? (ceil(($totalPedido * 1.19) / 10) * 10) : round($totalPedido * 1.19, 2);
                        ?>
                        <tr>
                            <td class="oc-totals-label">{{ $order->sin_iva ? $t['total'] : $t['net'] }}</td>
                            <td class="oc-totals-value">{!! $formatCurrency($totalPedido) !!}</td>
                        </tr>
                        @unless ($order->sin_iva)
                        <tr>
                            <td class="oc-totals-label">{{ $t['vat'] }}</td>
                            <td class="oc-totals-value">{!! $formatCurrency($ivaPedido) !!}</td>
                        </tr>
                        <tr class="oc-total-row">
                            <td class="oc-totals-label">{{ $t['total'] }}</td>
                            <td class="oc-totals-value">{!! $formatCurrency($totalConIva) !!}</td>
                        </tr>
                        @endunless
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
                <p class="oc-images-title">{{ $t['images'] }}</p>
                @foreach ($productsWithImages as $detail)
                    <p class="oc-images-product">{{ $detail->product }}</p>
                    @foreach ($detail->images as $image)
                        <img src="{{ public_path($image->imagen) }}" style="max-width: 300px; margin: 5px;">
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
