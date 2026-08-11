<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PurchaseOrder;
use App\Models\TipoPago;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    protected $company_defect;

    public function __construct()
    {
        $company_defect = new Company();
        $company_defect->rut = "76.515.046-9";
        $company_defect->razonSocial = "COMERCIAL SUPRA E.I.R.L";
        $company_defect->email = "ventas@comercialsupra.cl";
        $company_defect->phone = "+56989483379";
        $company_defect->address = "Avda. Ruben Jimenez 601";
        $company_defect->comuna = "Coquimbo";
        $company_defect->giro = "Repuestos Automotrices, Repuestos Maquinarias, Importaciones";
        $company_defect->url = "";
        $this->company_defect = $company_defect;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::id();
        $id = request('id');
        $razonSocial = request('razonSocial');
        $product = request('product');
        $dateFrom = request('date_from');
        $dateTo = request('date_to');
        $state = request('state');
        $perPage = (int) request('per_page', 20);
        if ($perPage <= 0) {
            $perPage = 20;
        }

        $purchaseOrders = DB::table('purchase_orders')
            ->leftJoin('clients', 'purchase_orders.supplier_id', '=', 'clients.id')
            ->select(
                'purchase_orders.id',
                'purchase_orders.user_id',
                'purchase_orders.supplier_id',
                'purchase_orders.correlativo',
                DB::raw("COALESCE(clients.rut, '') AS rut"),
                DB::raw("COALESCE(clients.razonSocial, '') AS razonSocial"),
                'purchase_orders.supplier_text',
                'purchase_orders.payment',
                'purchase_orders.state',
                'purchase_orders.created_at',
                'purchase_orders.url',
                'purchase_orders.telefono',
                'purchase_orders.order_number',
                'purchase_orders.buyer',
                'purchase_orders.currency',
                'purchase_orders.promised_date',
                'purchase_orders.shipping_method',
                'purchase_orders.payment_terms',
                'purchase_orders.requested_by',
                'purchase_orders.ship_to',
                'purchase_orders.observaciones',
                DB::raw("(SELECT COUNT(*) FROM purchase_order_details WHERE purchase_order_details.purchase_order_id = purchase_orders.id AND COALESCE(TRIM(purchase_order_details.product), '') <> '') AS detail_count"),
                DB::raw("(SELECT SUBSTRING_INDEX(GROUP_CONCAT(TRIM(purchase_order_details.product) ORDER BY purchase_order_details.id SEPARATOR '||'), '||', 5) FROM purchase_order_details WHERE purchase_order_details.purchase_order_id = purchase_orders.id AND COALESCE(TRIM(purchase_order_details.product), '') <> '') AS product_preview")
            )
            ->orderBy('purchase_orders.id', 'DESC')
            ->when($user_id != 1, function ($query) use ($user_id) {
                return $query->where('purchase_orders.user_id', '=', $user_id);
            })
            ->when($user_id == 1, function ($query) {
                return $query->whereNotIn('purchase_orders.user_id', User::where('is_independent', true)->pluck('id'));
            })
            ->when($id, function ($query, $id) {
                return $query->where('purchase_orders.id', 'like', '%' . $id . '%');
            })
            ->when($razonSocial, function ($query, $razonSocial) {
                return $query->where('clients.razonSocial', 'like', '%' . $razonSocial . '%');
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                return $query->whereDate('purchase_orders.created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                return $query->whereDate('purchase_orders.created_at', '<=', $dateTo);
            })
            ->when($product, function ($query, $product) {
                return $query->whereExists(function ($sub) use ($product) {
                    $sub->select(DB::raw(1))
                        ->from('purchase_order_details')
                        ->whereColumn('purchase_order_details.purchase_order_id', 'purchase_orders.id')
                        ->where('purchase_order_details.product', 'like', '%' . $product . '%');
                });
            })
            ->when($state, function ($query, $state) {
                return $query->where('purchase_orders.state', $state);
            })
            ->paginate($perPage);

        return [
            'pagination' => [
                'total'         => $purchaseOrders->total(),
                'current_page'  => $purchaseOrders->currentPage(),
                'per_page'      => $purchaseOrders->perPage(),
                'last_page'     => $purchaseOrders->lastPage(),
                'from'          => $purchaseOrders->firstItem(),
                'to'            => $purchaseOrders->lastItem(),
            ],
            'purchaseOrders' => $purchaseOrders
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $user_id = Auth::id();
            $correlativo = $this->nextCorrelativo($user_id);
            $payment = $this->resolvePaymentName($data['payment'] ?? null);
            $supplierId = $this->resolveSupplierId($data['supplier_id'] ?? null);
            $supplierText = trim($data['supplier_text'] ?? '');
            $url = trim($data['url'] ?? '');
            $telefono = preg_replace('/\s+/', '', trim($data['telefono'] ?? ''));

            $purchaseOrder = PurchaseOrder::create([
                'user_id' => $user_id,
                'supplier_id' => $supplierId,
                'correlativo' => $correlativo,
                'state' => 'Pendiente',
                'payment' => $payment,
                'supplier_text' => $supplierText,
                'url' => $url,
                'telefono' => $telefono,
                'generado' => 2,
                'order_number' => trim($data['order_number'] ?? ''),
                'buyer' => trim($data['buyer'] ?? ''),
                'currency' => trim($data['currency'] ?? '') ?: 'PESO CHILENO',
                'sin_iva' => !empty($data['sin_iva']),
                'flete' => (float) ($data['flete'] ?? 0),
                'promised_date' => $data['promised_date'] ?? null,
                'shipping_method' => trim($data['shipping_method'] ?? ''),
                'payment_terms' => trim($data['payment_terms'] ?? ''),
                'requested_by' => trim($data['requested_by'] ?? ''),
                'ship_to' => trim($data['ship_to'] ?? ''),
                'observaciones' => trim($data['observaciones'] ?? ''),
            ]);

            if ($purchaseOrder->order_number === '') {
                $seed = ((int) $user_id === 1) ? $purchaseOrder->id : $purchaseOrder->correlativo;
                $purchaseOrder->order_number = $this->formatOrderNumber($seed);
                $purchaseOrder->save();
            }

            return $purchaseOrder->id;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $this->authorizeOwner($purchaseOrder->user_id);

        return $purchaseOrder;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $this->authorizeOwner($purchaseOrder->user_id);

        $data = $request->all();

        if (array_key_exists('payment', $data)) {
            $data['payment'] = $this->resolvePaymentName($data['payment']);
        }

        if (array_key_exists('supplier_id', $data)) {
            $data['supplier_id'] = $this->resolveSupplierId($data['supplier_id']);
        }

        if (array_key_exists('supplier_text', $data)) {
            $data['supplier_text'] = trim($data['supplier_text'] ?? '');
        }

        if (array_key_exists('url', $data)) {
            $data['url'] = trim($data['url'] ?? '');
        }

        if (array_key_exists('telefono', $data)) {
            $data['telefono'] = preg_replace('/\s+/', '', trim($data['telefono'] ?? ''));
        }

        if (array_key_exists('promised_date', $data) && $data['promised_date'] === '') {
            $data['promised_date'] = null;
        }

        $purchaseOrder->update($data);

        return;
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $this->authorizeOwner($purchaseOrder->user_id);

        $purchaseOrder->delete();

        return response()->json(['message' => 'Orden de compra eliminada con éxito']);
    }

    public function details($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $this->authorizeOwner($purchaseOrder->user_id);

        return $purchaseOrder->details()->with('images')->get();
    }

    public function pdf($id)
    {
        try {
            $order = PurchaseOrder::findOrFail($id);
            $this->authorizeOwner($order->user_id);

            $products = $order->details()->with('images')->get();

            $company = Company::where('user_id', '=', $order->user_id)->first();
            if ($company == null) {
                $company = $this->company_defect;
            }

            $supplier = $order->supplier;
            $user = User::where('id', '=', $order->user_id)->first();
            $lang = request('lang') === 'en' ? 'en' : 'es';

            $pdf = Pdf::loadView('pdf.purchase_order', compact(['company', 'order', 'supplier', 'products', 'user', 'lang']))
                ->setPaper('a4', 'portrait');

            // Numeracion de pagina via el canvas de dompdf (enable_php esta apagado
            // globalmente por seguridad, asi que no se puede usar <script type="text/php">).
            $pdf->render();
            $pageText = $lang === 'en' ? 'Page {PAGE_NUM} of {PAGE_COUNT}' : 'Página {PAGE_NUM} de {PAGE_COUNT}';
            $pdf->getDomPDF()->getCanvas()->page_text(480, 15, $pageText, null, 8, [0.03, 0.11, 0.25]);

            if ($order->user_id === 1) {
                return $pdf->stream('orden de compra N° ' . $order->id . '.pdf');
            } else {
                return $pdf->stream('orden de compra N° ' . $order->correlativo . '.pdf');
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function replicate($id)
    {
        $order = PurchaseOrder::with('details')->findOrFail($id);
        $currentUserId = Auth::id();

        if ((int) $currentUserId !== 1 && (int) $order->user_id !== (int) $currentUserId) {
            abort(403);
        }

        $newOrder = DB::transaction(function () use ($order) {
            $newOrder = PurchaseOrder::create([
                'user_id' => $order->user_id,
                'supplier_id' => $order->supplier_id,
                'correlativo' => $this->nextCorrelativo($order->user_id),
                'state' => 'Pendiente',
                'payment' => $this->resolvePaymentName($order->payment),
                'supplier_text' => trim($order->supplier_text ?? ''),
                'url' => trim($order->url ?? ''),
                'telefono' => preg_replace('/\s+/', '', trim($order->telefono ?? '')),
                'generado' => $order->generado,
                'buyer' => $order->buyer,
                'currency' => $order->currency,
                'shipping_method' => $order->shipping_method,
                'payment_terms' => $order->payment_terms,
                'requested_by' => $order->requested_by,
                'ship_to' => $order->ship_to,
                'observaciones' => $order->observaciones,
            ]);

            $seed = ((int) $order->user_id === 1) ? $newOrder->id : $newOrder->correlativo;
            $newOrder->order_number = $this->formatOrderNumber($seed);
            $newOrder->save();

            foreach ($order->details as $detail) {
                $newOrder->details()->create([
                    'product' => $detail->product,
                    'detail' => $detail->detail,
                    'price' => $detail->price,
                    'quantity' => $detail->quantity,
                    'total' => $detail->total,
                    'days' => $detail->days,
                ]);
            }

            return $newOrder;
        });

        return response()->json(['id' => $newOrder->id]);
    }

    private function nextCorrelativo($userId)
    {
        if ((int) $userId === 1) {
            return 0;
        }

        $purchaseOrder = PurchaseOrder::where('user_id', '=', $userId)
            ->select('correlativo')
            ->latest()
            ->first();

        if ($purchaseOrder === null) {
            return 0;
        }

        return ((int) $purchaseOrder->correlativo) + 1;
    }

    private function formatOrderNumber($number)
    {
        return 'OC-' . str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Vista previa del proximo numero correlativo, para mostrarlo como
     * placeholder editable en el formulario antes de guardar la orden.
     */
    public function nextOrderNumber()
    {
        $userId = Auth::id();

        if ((int) $userId === 1) {
            $next = (int) PurchaseOrder::max('id') + 1;
        } else {
            $next = $this->nextCorrelativo($userId);
        }

        return response()->json(['order_number' => $this->formatOrderNumber($next)]);
    }

    private function resolvePaymentName($payment)
    {
        if ($payment === null || $payment === '') {
            return 'Contado';
        }

        if (is_numeric($payment)) {
            $tipoPago = TipoPago::find($payment);

            return $tipoPago ? $tipoPago->pago : 'Contado';
        }

        return trim((string) $payment);
    }

    private function resolveSupplierId($supplierId)
    {
        if ($supplierId === null || $supplierId === '') {
            return null;
        }

        return $supplierId;
    }
}
