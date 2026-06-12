<table>
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Dólar</th>
        <th>Fecha</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $import->name }}</td>
            <td>{{ $import->dolar }}</td>
            <td>{{ $import->created_at }}</td>
        </tr>
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>Producto</th>
        <th>Detalle</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>% Imp Usa</th>
        <th>Seguro</th>
        <th>Ad Valorem</th>
        <th>Adicional</th>
        <th>Embarque</th>
        <th>Fee</th>
        <th>Flete Usa</th>
        <th>BankUsa</th>
        <th>BankChile</th>
        <th>Transferencia</th>
        <th>Otro</th>
        <th>Aduana 1</th>
        <th>Aduana 2</th>
        <th>Manipuleo</th>
        <th>Bodega</th>
        <th>Guia</th>
        <th>Retiro</th>
        <th>Flete Chile</th>
        <th>% </th>
        <th>Internacional</th>
        <th>Nacional</th>
        <th>Costo Total</th>
        <th>Valor Unitario</th>
        <th>Valor Total</th>
        <th>Valor Chile</th>
        <th>Utilidad</th>
    </tr>
    </thead>
    <tbody>
    <?php $totalPorcentaje = 0 ?>
    <?php $totalInternacional = 0 ?>
    <?php $totalNacional = 0 ?>
    <?php $totalCosto = 0 ?>
    <?php $total = 0 ?>
    <?php $totalUtilidad = 0 ?>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->product }}</td>
            <td>{{ $product->detail }}</td>
            <td>{{ $product->price_dolar }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->usa * 100 }}</td>
            <td>{{ $product->seguro * 100 }}</td>
            <td>{{ $product->valorem }}</td>
            <td>{{ $product->aditional }}</td>
            <td>{{ $product->embarque }}</td>
            <td>{{ $product->fee }}</td>
            <td>{{ $product->fleteUsa }}</td>
            <td>{{ $product->bankusa }}</td>
            <td>{{ $product->bankchile }}</td>
            <td>{{ $product->transferencia }}</td>
            <td>{{ $product->otro }}</td>
            <td>{{ $product->aduana1 }}</td>
            <td>{{ $product->aduana2 }}</td>
            <td>{{ $product->manipuleo }}</td>
            <td>{{ $product->bodega }}</td>
            <td>{{ $product->guia }}</td>
            <td>{{ $product->retiro }}</td>
            <td>{{ $product->fleteChile }}</td>
            <td>{{ $product->percentage }}</td>
            <?php $totalPorcentaje += $product->percentage; ?>
            <td>{{ $product->internacional }}</td>
            <?php $totalInternacional += $product->internacional; ?>
            <td>{{ $product->nacional }}</td>
            <?php $totalNacional += $product->nacional; ?>
            <td>{{ $product->costTotal }}</td>
            <?php $totalCosto += $product->costTotal; ?>
            <td>{{ $product->total / $product->quantity }}</td>
            <td>{{ $product->total }}</td>
            <?php $total += $product->total; ?>
            <td>{{ $product->valueChile }}</td>
            <td>{{ $product->utility }}</td>
            <?php $totalUtilidad += $product->utility; ?>
        </tr>
    @endforeach
    <tr>
        <th>Totales</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th><?php echo $totalPorcentaje; ?></th>
        <th><?php echo $totalInternacional; ?></th>
        <th><?php echo $totalNacional; ?></th>
        <th><?php echo $totalCosto; ?></th>
        <th></th>
        <th><?php echo $total; ?></th>
        <th></th>
        <th><?php echo $totalUtilidad; ?></th>
    </tr>
    </tbody>
</table>
