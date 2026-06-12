<?php

namespace Services;

use App\Boleta;
use DateTime;

class InvoiceGenerateService
{

    /** @var DateTime $date */
    protected $date;

    /** @var string $lineOfBusiness */
    protected $lineOfBusiness;

    /** @var string $address */
    protected $address;

    /** @var string $rut */
    protected $rut;

    /** @var string $products */
    protected $products;

    /** @var int $quantity */
    protected $quantity;

    /** @var int $price */
    protected $price;

    /** @var int $total */
    protected $total;


    /**
     * Obtiene la fecha del documento 
     */
    protected function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Esteblece la fecha del documento 
     */
    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Obtiene el giro comercial de la empresa
     */
    protected function getLineOfBusiness(): string
    {
        return $this->lineOfBusiness;
    }

    /**
     * Esteblece el giro comercial de la empresa
     */
    public function setLineOfBusiness(string $lineOfBusiness): self
    {
        $this->lineOfBusiness = $lineOfBusiness;

        return $this;
    }

    /**
     * Obtiene la dirección de la empresa
     */
    protected function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Esteblece la dirección de la empresa
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Obtiene el RUT de la empresa 
     */
    protected function getRut(): string
    {
        return $this->rut;
    }

    /**
     * Esteblece el RUT de la empresa
     */
    public function setRut(string $rut): self
    {
        $this->rut = $rut;

        return $this;
    }

    /**
     * Obtiene  cadena de productos
     */
    protected function getProducts(): string
    {
        return $this->products;
    }


    /**
     * Esteblece cadena de productos
     */
    public function setProducts(string $products): self
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Obtiene cantidad de productos
     */
    protected function getQuantity(): string
    {
        return $this->quantity;
    }


    /**
     * Esteblece arreglo de productos
     */
    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }


    /**
     * Obtiene el precio del producto
     */
    protected function getPrice(): int
    {
        return $this->price;
    }


    /**
     * Esteblece el precio del producto
     */
    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Obtiene el monto total del documento
     */
    protected function getTotal(): int
    {
        return $this->total;
    }

    /**
     * Esteblece el monto total del documento
     */
    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Genera boleta con el monto total de la compra.
     * Formato 80MM. tipo Voucher
     */
    public function generateBasicInvoice()
    {
        $date = $this->date;
        $rut = $this->rut;
        $total = $this->total;
        $neto = round(($this->total / 119) * 100);
        $iva = round(($this->total / 119) * 19);

        
    

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

        $path = realpath('invoice');
        $pdf = fopen($path . "/" . $fileName, "w");
        
        fwrite($pdf, $pdf_decoded);
        fclose($pdf);

        $this->saveFile($fileName);
        return $fileName;
    }


    /**
     * Genera boleta con detalle de compra
     */
    public function generateDocument()
    {
        $date = $this->date;
        $lineOfBusiness = $this->lineOfBusiness;
        $address = $this->address;
        $rut = $this->rut;
        $products = $this->products;
        $total = $this->total;

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');

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
            CURLOPT_POSTFIELDS => "{\"response\":[\"PDF\"],\"dte\":{\"Encabezado\":{\"IdDoc\":{\"TipoDTE\":39,\"Folio\":0,\"FchEmis\":\"{$date}\",\"IndServicio\":\"3\"},\"Emisor\":{\"RUTEmisor\":\"76795561-8\",\"RznSocEmisor\":\"HAULMER SPA\",\"GiroEmisor\":\"{$lineOfBusiness};COMERCIOELEC\",\"CdgSIISucur\":\"81303347\",\"DirOrigen\":\"{$address}\",\"CmnaOrigen\":\"Curicó\"},\"Receptor\":{\"RUTRecep\":\"{$rut}\"},\"Totales\":{\"MntTotal\":{$total},\"TotalPeriodo\":{$total},\"VlrPagar\":{$total}}},\"Detalle\":[{$products}]}}",
            CURLOPT_HTTPHEADER => array(
                "apikey: 928e15a2d14d4a6292345f04960f4bd3",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response;
        $created = new DateTime();

        $time = $created->format('G.i.s');
        $month = $created->format('d-m-Y');
        $fileName = "Boleta-" . $rut . "-" . $month . "-" . $time . ".pdf";

        $pdf_decoded = base64_decode($response);

        $path = realpath('invoice');
        $pdf = fopen($path . "/" . $fileName, "w");
        fwrite($pdf, $pdf_decoded);
        fclose($pdf);

        $this->saveFile($fileName);

        echo $fileName;
    }

    /**
     * Guarda la ruta del archivo generado
     * @param string $fileName
     */
    public function saveFile($filename)
    {
        $boleta = new Boleta();
        $dateSave = new DateTime();
        $created = $dateSave->format('d-m-Y');
        $boleta->fecha = $created;
        $boleta->ruta = "invoice/" . $filename;
        $boleta->save();
    }
}
