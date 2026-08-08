<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if ($quotation->user_id === 1)
        <title>Cotizacion N°{{ $quotation->id }}</title>
    @else
        <title>Cotizacion N°{{ $quotation->correlativo }}</title>
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

        /* ---- bloques cliente / vehiculo ---- */
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

        /* ---- datos del cliente en 2 columnas (cuando no hay vehiculo) ---- */
        .oc-info-2col td {
            vertical-align: top;
            width: 50%;
        }

        .oc-info-2col-left {
            padding-right: 10px;
        }

        .oc-info-2col-right {
            padding-left: 10px;
        }

        /* ---- fila de metadatos (forma de pago) ---- */
        .oc-extra-row {
            margin-top: 8px;
            border: 1px solid #D9DEE7;
            padding: 6px 8px;
        }

        .oc-extra-row td {
            font-size: 8.5px;
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
        }

        .oc-col-code {
            text-align: left;
            width: 13%;
        }

        .oc-col-desc {
            text-align: left;
            width: 33%;
        }

        .oc-col-part {
            text-align: left;
            width: 14%;
        }

        .oc-col-qty {
            text-align: center;
            width: 5%;
        }

        .oc-col-days {
            text-align: center;
            width: 12%;
        }

        .oc-col-price {
            text-align: right;
            width: 10%;
        }

        .oc-col-total {
            text-align: right;
            width: 12%;
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
        }

        .oc-total-row td {
            background-color: #071B41;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 10px;
            border-color: #071B41;
        }

        /* ---- repuestos solicitados ---- */
        .oc-spare-box {
            border: 1px solid #D9DEE7;
            padding: 8px;
            margin-top: 10px;
        }

        .oc-spare-box p {
            font-size: 8.5px;
            color: #333333;
            margin: 2px 0;
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

        /* ---- barra inferior (fija al pie de cada pagina) ---- */
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
