<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if ($order->user_id === 1)
        <title>Orden de Compra N°{{ $order->id }}</title>
    @else
        <title>Orden de Compra N°{{ $order->correlativo }}</title>
    @endif

    <style>
        /* dompdf: sin flexbox/grid/JS, todo con tablas + CSS basico */
        @page {
            margin: 12mm 10mm 12mm 10mm;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #1A1A1A;
            font-size: 9px;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        p {
            margin: 0;
        }

        /* ---- encabezado ---- */
        .oc-header-table td {
            vertical-align: top;
        }

        .oc-company-name {
            font-size: 14px;
            font-weight: bold;
            color: #071B41;
            margin: 4px 0 2px 0;
        }

        .oc-company-giro {
            font-size: 9px;
            color: #555555;
        }

        .oc-title {
            font-size: 20px;
            font-weight: bold;
            color: #071B41;
            text-align: right;
            letter-spacing: 1px;
        }

        .oc-number {
            font-size: 14px;
            font-weight: bold;
            color: #071B41;
            text-align: right;
            margin-top: 3px;
        }

        .oc-meta {
            font-size: 12px;
            color: #444444;
            text-align: right;
            margin-top: 3px;
        }

        .oc-hr {
            border: none;
            border-top: 1.5px solid #071B41;
            margin: 6px 0 10px 0;
        }

        /* ---- bloques proveedor / entrega ---- */
        .oc-info-table td {
            vertical-align: top;
            width: 50%;
        }

        .oc-info-table td:first-child {
            padding-right: 6px;
        }

        .oc-info-table td:last-child {
            padding-left: 6px;
        }

        .oc-info-box {
            border: 1px solid #D9DEE7;
            padding: 8px;
        }

        .oc-info-title {
            color: #071B41;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 6px;
            border-bottom: 1px solid #D9DEE7;
            padding-bottom: 4px;
        }

        .oc-info-box table td {
            width: auto;
            padding: 1.5px 0;
            font-size: 10px;
            vertical-align: top;
            text-align: left;
        }

        .oc-info-label {
            color: #666666;
            width: 1%;
            white-space: nowrap;
            padding-right: 6px;
        }

        .oc-info-value {
            font-weight: bold;
            color: #1A1A1A;
        }

        .oc-info-value.oc-info-value-inline {
            padding-right: 20px;
            width: 1%;
            white-space: nowrap;
        }

        /* ---- fila de metadatos (envio / pago / solicitado por) ---- */
        .oc-extra-row {
            margin-top: 8px;
            border: 1px solid #D9DEE7;
            padding: 6px 8px;
        }

        .oc-extra-row td {
            font-size: 10px;
            text-align: left;
            vertical-align: top;
        }

        .oc-extra-label {
            color: #666666;
            width: 1%;
            white-space: nowrap;
            padding-right: 4px;
        }

        .oc-extra-value {
            font-weight: bold;
            color: #1A1A1A;
            width: 1%;
            white-space: nowrap;
            padding-right: 22px;
        }

        /* ---- tabla de productos ---- */
        .oc-lead {
            font-size: 10px;
            font-weight: bold;
            color: #071B41;
            margin: 12px 0 6px 0;
        }

        .oc-products {
            margin-top: 4px;
            table-layout: fixed;
        }

        .oc-products thead {
            display: table-header-group;
        }

        .oc-products thead th {
            background-color: #071B41;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            padding: 5px 4px;
            border: 1px solid #071B41;
            white-space: nowrap;
        }

        .oc-products tbody tr {
            page-break-inside: avoid;
        }

        .oc-products tbody td {
            border: 1px solid #D9DEE7;
            padding: 4px;
            font-size: 10px;
            vertical-align: middle;
        }

        .oc-col-num {
            text-align: center;
            width: 5%;
            white-space: nowrap;
        }

        .oc-col-code {
            text-align: left;
            width: 13%;
            white-space: nowrap;
        }

        .oc-col-desc {
            text-align: left;
            width: 50%;
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
        }

        .oc-col-qty {
            text-align: center;
            width: 5%;
            white-space: nowrap;
        }

        .oc-col-days {
            text-align: center;
            width: 9.5%;
            white-space: nowrap;
        }

        .oc-col-price {
            text-align: right;
            width: 8%;
            white-space: nowrap;
        }

        .oc-col-total {
            text-align: right;
            width: 9.5%;
            white-space: nowrap;
        }

        /* ---- observaciones + totales ---- */
        .oc-bottom-table {
            margin-top: 10px;
        }

        .oc-bottom-table td {
            vertical-align: top;
        }

        .oc-obs-col {
            width: 60%;
            padding-right: 8px;
        }

        .oc-totals-col {
            width: 40%;
            padding-left: 8px;
        }

        .oc-obs-box {
            border: 1px solid #D9DEE7;
            padding: 8px;
        }

        .oc-obs-title {
            color: #071B41;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 6px;
        }

        .oc-obs-box p {
            font-size: 10px;
            color: #333333;
            margin: 2px 0;
        }

        .oc-totals-table td {
            padding: 4px 8px;
            font-size: 9px;
            border: 1px solid #D9DEE7;
        }

        .oc-totals-label {
            text-align: left;
            color: #444444;
        }

        .oc-totals-value {
            text-align: right;
            font-weight: bold;
            white-space: nowrap;
        }

        .oc-total-row td {
            background-color: #071B41;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 10px;
            border-color: #071B41;
        }

        /* ---- imagenes de productos ---- */
        .oc-images-title {
            font-size: 10px;
            font-weight: bold;
            color: #071B41;
        }

        .oc-images-product {
            font-size: 9px;
            font-weight: bold;
            margin: 8px 0 2px 0;
        }

        /* ---- barra inferior ---- */
        .oc-footer-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #071B41;
            color: #FFFFFF;
            text-align: center;
            padding: 6px;
            font-size: 14px;
        }
    </style>

</head>

<body>
    @yield('content')
</body>

</html>
