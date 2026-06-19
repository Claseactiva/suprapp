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
        table {
            width: 100%;
        }

        td{
            text-align: center;
        }
        
        .title-quotation{
            padding-right: 30px;
        }

        p{
            margin: 5px;
        }

        .separate {
            padding: 20px 
        }

        .separate-2 {
            padding: 10px 
        }

        .padding {
            padding: 10px 0px;
        }

        .bold {
            font-weight: 800;
        }

        .border {
            width: 150px;
            border: 2px solid black;
        }

        .border-light {
            border: 1px solid black;
        }

        .background {
            background: gainsboro;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-size{
            font-size: 12px;
        }

        .product{
            width: 100%;
            border-collapse: collapse; 
        }

        .product thead {
            background: blue;
        }

        .product th {
            border: 1px solid black;
            color: white;
        }

        .product td{
            border: 1px solid black;
            font-size: 12px;
        }

        .product tr .cell{
            width: 10%;
        }

        .product p{
            border: 1px solid black;
            font-size: 12px;
        }
    </style>

</head>

<body>
    @yield('content')
</body>

</html>
