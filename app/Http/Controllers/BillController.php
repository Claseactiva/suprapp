<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Boleta;
use App\Models\Client;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{

    public function store(Request $request)
    {
        try {
            $path = $request->file('file');
            $xml = simplexml_load_file($path);
            $utilityDefect = $request->utility;
            $fleteDefect = $request->flete;

            $client_id = Client::updateOrCreate(
                ['rut' => $xml->DTE->Documento->Encabezado->Emisor[0]->RUTEmisor],
                [
                    'user_id' => Auth::id(),
                    'name' => $xml->DTE->Documento->Encabezado->Emisor[0]->RznSoc,
                    'razonSocial' => $xml->DTE->Documento->Encabezado->Emisor[0]->RznSoc,
                    'address' => $xml->DTE->Documento->Encabezado->Emisor[0]->DirOrigen,
                    'comuna' => $xml->DTE->Documento->Encabezado->Emisor[0]->CmnaOrigen,
                    'giro' => $xml->DTE->Documento->Encabezado->Emisor[0]->GiroEmis,
                    'type' => 'Proveedor'
                ]
            )->id;

            foreach ($xml->DTE->Documento->Detalle as $invoice) {
                $name_product = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $invoice->NmbItem);

                if (!empty($invoice->CdgItem->TpoCodigo)) {
                    $count = Product::where('codebar', $invoice->CdgItem->TpoCodigo . '-' . $invoice->CdgItem->VlrCodigo)->count();

                    if (empty($count)) {
                        $product = $this->addProduct($name_product, $client_id, $invoice, $xml, $utilityDefect, $fleteDefect);
                        $this->addInventory($product, $invoice, $xml);
                    } else {
                        $products = Product::where('codebar', $invoice->CdgItem->TpoCodigo . '-' . $invoice->CdgItem->VlrCodigo)->get();
                        foreach ($products as $product) {

                            if ($product->attribute > 0) {
                                $this->updateInventoryForAttribute($product, $invoice);
                            } else {
                                $this->addInventory($product, $invoice, $xml);
                            }
                        }
                    }
                } else {
                    $count = Product::where('name', $name_product)->count();

                    if (empty($count)) {
                        $product = $this->addProduct($name_product, $client_id, $invoice, $xml, $utilityDefect, $fleteDefect);
                        $this->addInventory($product, $invoice, $xml);
                    } else {
                        $products = Product::where('name', $invoice->NmbItem)->get();
                        foreach ($products as $product) {

                            if ($product->attribute > 0) {
                                $this->updateInventoryForAttribute($product, $invoice);
                            } else {
                                $this->addInventory($product, $invoice, $xml);
                            }
                        }
                    }
                }
            }

            return response()->json(['message' => 'Factura ingresada correctamente'], 200);
        } catch (\Exception $e) {
            // En caso de error, devolver una respuesta de error con un mensaje apropiado.
            return response()->json(['error' => 'Ocurrió un error al procesar la factura: ' . $e->getMessage()], 500);
        }
    }


    public function addProduct($name, $client_id, $invoice, $xml, $utility, $flete)
    {
        $codebar = isset($invoice->CdgItem->TpoCodigo) ? $invoice->CdgItem->TpoCodigo . '-' . $invoice->CdgItem->VlrCodigo : 'Sin codigo';

        $product = Product::firstOrCreate(
            [
                'name' => $name,
                'client_id' => $client_id,
                'codebar' => $codebar,
                'detail' => $codebar,
                'utilidad' => $utility,
                'flete' => $flete,
                'folio' => $xml->DTE->Documento->Encabezado->IdDoc[0]->Folio,
            ]
        );

        return $product;
    }


    public function addInventory($product, $invoice, $xml)
    {
        Inventory::firstOrCreate(
            [
                'product_id' => $product->id,
                'price' => round($invoice->MontoItem / $invoice->QtyItem),
                'quantity' => $invoice->QtyItem,
                'fecha_fact' => $xml->DTE->Documento->Encabezado->IdDoc[0]->FchEmis
            ]
        );
    }


    public function updateInventoryForAttribute($product, $invoice)
    {
        $quantity = $invoice->QtyItem * $product->attribute;
        $total = $invoice->PrcItem * $invoice->QtyItem;

        Inventory::where('product_id', '=', $product->id)->update(
            [
                'price' => $total / $quantity,
                'quantity' => $quantity
            ]
        );
    }

    public function getDataInvoice(Request $request)
    {
        $fecha = $request->fecha  === null ? new DateTime() : new DateTime($request->fecha);
        $date = $fecha->format('Y-m-d');
        $rut = $this->formatRut($request->rutReceptor);
        $totalInvoice = $request->sumaTotalBoleta;

        return  $this->generateBasicInvoice($date, $rut, $totalInvoice);
    }

    public function generateBasicInvoice($date, $rut, $totalInvoice)
    {
        $date = $date;
        $rut = $rut;
        $total = $totalInvoice;
        $neto = round(($total / 119) * 100);
        $iva = round(($total / 119) * 19);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://dev-api.haulmer.com/v2/dte/document",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{
                \"response\":[
                    \"PDF\" , 
                    \"80MM\"
                ],
                \"dte\": {
                    \"Encabezado\":{
                        \"IdDoc\": {
                            \"TipoDTE\": 39,
                            \"Folio\": 0,
                            \"FchEmis\": \"{$date}\",
                            \"IndServicio\": \"3\"
                        },
                        \"Emisor\": {
                            \"RUTEmisor\": \"76795561-8\",
                            \"RznSocEmisor\": \"HAULMER SPA\",
                            \"GiroEmisor\": \"VENTA AL POR MENOR EN EMPRESAS DE VENTA A DISTANCIA VÍA INTERNET\",
                            \"CdgSIISucur\": \"81303347\",
                            \"DirOrigen\": \"ARTURO PRAT 527 CURICO\",
                            \"CmnaOrigen\": \"Curicó\"
                        },
                        \"Receptor\": {
                            \"RUTRecep\": \"66666666-6\"
                        },
                        \"Totales\": {
                            \"MntNeto\": {$neto},
                            \"IVA\": {$iva},
                            \"MntTotal\": {$total},
                            \"VlrPagar\": {$total}
                        }
                    },
                    \"Detalle\": [
                        {
                            \"NroLinDet\": 1,
                            \"NmbItem\": \"Ventas\",
                            \"QtyItem\": 1,
                            \"PrcItem\": {$total},
                            \"MontoItem\": {$total}
                        }
                    ]
                }
            }",
            CURLOPT_HTTPHEADER => array(
                "apikey: 928e15a2d14d4a6292345f04960f4bd3",
                "Content-Type: application/json"
            ),
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        $created = new DateTime();

        $time = $created->format('G.i.s');
        $month = $created->format('d-m-Y');
        $fileName = "Boleta-" . $rut . "-" . $month . "-" . $time . ".pdf";
        $pdf_decoded = base64_decode($response);

        $path = storage_path('app/public/images/invoice');
        $pdf = fopen($path . "/" . $fileName, "w");
        
        fwrite($pdf, $pdf_decoded);
        fclose($pdf);

        $ruta = $this->saveFile($fileName);
        return $ruta;
    }


    public function saveFile($filename)
    {
        $boleta = new Boleta();
        $dateSave = new DateTime();
        $created = $dateSave->format('d-m-Y');
        $boleta->fecha = $created;
        $boleta->ruta = 'app/public/images/invoice/' . $filename;
        $boleta->save();
        return 'app/public/images/invoice/' . $filename;
    }

    public function formatRut($partialRut)
    {
        $rut = str_replace('-', '', str_replace('.', '', $partialRut));
        $verificatorDigit = substr($rut, -1);
        $numerRut = substr($rut, 0, -1);
        $finalRut =  $numerRut . "-" . $verificatorDigit;
        return $finalRut;
    }
}
