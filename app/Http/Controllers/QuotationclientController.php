<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Client;
use App\User;
use App\Models\Quotationclient;
use App\Models\QuotationUser;
use App\Models\QuotationUserVehicle;
use App\Models\VehicleModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class QuotationclientController extends Controller
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
        $client = request('client');
        $vehicle = request('vehicle');
        $day = request('day');
        $month = request('month');
        $year = request('year');


        if ($user_id == 1)
            $quotationclients = DB::table('quotationclients')
                ->join('clients', 'quotationclients.client_id', '=', 'clients.id')
                ->select(
                    'quotationclients.id',
                    'quotationclients.user_id',
                    'quotationclients.correlativo',
                    'clients.rut',
                    'clients.razonSocial',
                    'quotationclients.client_text',
                    'quotationclients.vehicle',
                    'quotationclients.payment',
                    'quotationclients.state',
                    'quotationclients.generado_client',
                    'quotationclients.tipo_detalle',
                    'quotationclients.generado',
                    'quotationclients.created_at',
                    'quotationclients.url'
                )
                ->orderBy('quotationclients.id', 'DESC')
                ->where('quotationclients.generado', '<>', 3)
                ->where('quotationclients.generado', '<>', 5)
                ->when($id, function ($query, $id) {
                    return $query->where('quotationclients.id', 'like', '%' . $id . '%');
                })
                ->when($razonSocial, function ($query, $razonSocial) {
                    return $query->where('clients.razonSocial', 'like', '%' . $razonSocial . '%');
                })
                ->when($client, function ($query, $client) {
                    return $query->where('quotationclients.client_text', 'like', '%' . $client . '%');
                })
                ->when($vehicle, function ($query, $vehicle) {
                    return $query->where('quotationclients.vehicle', 'like', '%' . $vehicle . '%');
                })
                ->when($day, function ($query, $day) {
                    return $query->whereDay('quotationclients.created_at', $day);
                })
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('quotationclients.created_at', $month);
                })
                ->when($year, function ($query, $year) {
                    return $query->whereYear('quotationclients.created_at', $year);
                })
                ->paginate(10);

        else

            $quotationclients = DB::table('quotationclients')
                ->join('clients', 'quotationclients.client_id', '=', 'clients.id')
                ->select(
                    'quotationclients.id',
                    'quotationclients.user_id',
                    'quotationclients.correlativo',
                    'clients.rut',
                    'clients.razonSocial',
                    'quotationclients.client_text',
                    'quotationclients.vehicle',
                    'quotationclients.payment',
                    'quotationclients.state',
                    'quotationclients.generado_client',
                    'quotationclients.tipo_detalle',
                    'quotationclients.generado',
                    'quotationclients.created_at',
                    'quotationclients.url'
                )
                ->orderBy('quotationclients.id', 'DESC')
                ->where('quotationclients.generado', '<>', 3)
                ->where('quotationclients.user_id', '=', $user_id)
                ->when($id, function ($query, $id) {
                    return $query->where('quotationclients.id', 'like', '%' . $id . '%');
                })
                ->when($razonSocial, function ($query, $razonSocial) {
                    return $query->where('clients.razonSocial', 'like', '%' . $razonSocial . '%');
                })
                ->when($client, function ($query, $client) {
                    return $query->where('quotationclients.client_text', 'like', '%' . $client . '%');
                })
                ->when($vehicle, function ($query, $vehicle) {
                    return $query->where('quotationclients.vehicle', 'like', '%' . $vehicle . '%');
                })
                ->when($day, function ($query, $day) {
                    return $query->whereDay('quotationclients.created_at', $day);
                })
                ->when($month, function ($query, $month) {
                    return $query->whereMonth('quotationclients.created_at', $month);
                })
                ->when($year, function ($query, $year) {
                    return $query->whereYear('quotationclients.created_at', $year);
                })
                ->paginate(10);

        return [
            'pagination' => [
                'total'         => $quotationclients->total(),
                'current_page'  => $quotationclients->currentPage(),
                'per_page'      => $quotationclients->perPage(),
                'last_page'     => $quotationclients->lastPage(),
                'from'          => $quotationclients->firstItem(),
                'to'            => $quotationclients->lastItem(),
            ],
            'quotationclients' => $quotationclients
        ];
    }

    public function clientsform()
    {
        $id = request('id');
        $razonSocial = request('razonSocial');
        $client = request('client');
        $vehicle = request('vehicle');
        $day = request('day');
        $month = request('month');
        $year = request('year');

        $quotationclientsform = DB::table('quotationclients')
            ->join('clients', 'quotationclients.client_id', '=', 'clients.id')
            ->select(
                'quotationclients.id',
                'clients.rut',
                'clients.razonSocial',
                'quotationclients.client_text',
                'quotationclients.vehicle',
                'quotationclients.payment',
                'quotationclients.state',
                'quotationclients.generado_client',
                'quotationclients.tipo_detalle',
                'quotationclients.generado',
                'quotationclients.created_at'
            )
            ->orderBy('quotationclients.id', 'DESC')
            ->where('quotationclients.generado', '=', 3)
            ->orWhere('quotationclients.generado', '=', 5)
            ->when($id, function ($query, $id) {
                return $query->where('quotationclients.id', 'like', '%' . $id . '%');
            })
            ->when($razonSocial, function ($query, $razonSocial) {
                return $query->where('clients.razonSocial', 'like', '%' . $razonSocial . '%');
            })
            ->when($client, function ($query, $client) {
                return $query->where('quotationclients.client_text', 'like', '%' . $client . '%');
            })
            ->when($vehicle, function ($query, $vehicle) {
                return $query->where('quotationclients.vehicle', 'like', '%' . $vehicle . '%');
            })
            ->when($day, function ($query, $day) {
                return $query->whereDay('quotationclients.created_at', $day);
            })
            ->when($month, function ($query, $month) {
                return $query->whereMonth('quotationclients.created_at', $month);
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('quotationclients.created_at', $year);
            })
            ->paginate(10);


        return [
            'pagination_form' => [
                'total'         => $quotationclientsform->total(),
                'current_page'  => $quotationclientsform->currentPage(),
                'per_page'      => $quotationclientsform->perPage(),
                'last_page'     => $quotationclientsform->lastPage(),
                'from'          => $quotationclientsform->firstItem(),
                'to'            => $quotationclientsform->lastItem(),
            ],
            'quotationclientsform' => $quotationclientsform
        ];
    }


    public function store(Request $request)
    {
        try {

            $data = $request->all();
            $user_id = Auth::id();
            $correlativo = 0;

            $quotationclient = Quotationclient::where('user_id', '=', $user_id)->where('user_id', '!=', 1)->select('correlativo')->latest()->first();

            if ($quotationclient != null) {
                $correlativo = $quotationclient->correlativo + 1;
            }

            $roles = DB::table('roles')
                ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                ->where('users.id', '=', $user_id)
                ->select('roles.id')
                ->get();

            foreach ($roles as $rol) {
                if ($rol->id == 2 && isset($data['cliente_part'])) {
                    $data['generado'] = 1;
                    $data['tipo_detalle'] = 1;
                } else {
                    if ($data['client_id'] == 1) {
                        $data['generado'] = 1;
                    } else {
                        $data['generado'] = 2;
                    }
                }
            }


            if (isset($data['cliente_part'])) {
                $clients = Client::where('user_id', '=', $user_id)->where('type', '=', 'Cliente Particular')->get();

                if ($clients->count() == 0) {
                    $client_id = Client::create([
                        'user_id' => $user_id,
                        'type' => 'Cliente Particular',
                        'rut' => null,
                        'razonSocial' => 'Cliente Particular',
                        'phone' => null,
                        'telefono' => null,
                        'email' => null,
                        'address' => null,
                        'comuna' => null,
                        'giro' => 'Cliente Particular',
                    ])->id;

                    $quotation_id = Quotationclient::create([
                        'user_id' => $user_id,
                        'client_id' => $client_id,
                        'correlativo' => $correlativo,
                        'state' => 'Pendiente',
                        'payment' => 'Contado',
                        'client_text' => $data['client_text'],
                        'vehicle' => $data['vehicle'],
                        'generado' => $data['generado'],
                        'url' => $data['url'],
                        'ppu' => $data['ppu']
                    ])->id;
                } else {
                    foreach ($clients as $client) {
                        $quotation_id = Quotationclient::create(
                            [
                                'user_id' => $user_id,
                                'client_id' => $client->id,
                                'correlativo' => $correlativo,
                                'state' => 'Pendiente',
                                'payment' => 'Contado',
                                'client_text' => $data['client_text'],
                                'vehicle' => $data['vehicle'],
                                'generado' => $data['generado'],
                                'url' => $data['url'],
                                'ppu' => $data['ppu']
                            ]
                        )->id;
                    }
                }
            } else {
                $quotation_id = Quotationclient::create(
                    [
                        'user_id' => $user_id,
                        'client_id' => $data['client_id'],
                        'correlativo' => $correlativo,
                        'state' => 'Pendiente',
                        'payment' => 'Contado',
                        'client_text' => $data['client_text'],
                        'vehicle' => $data['vehicle'],
                        'generado' => $data['generado'],
                        'url' => $data['url'],
                        'ppu' => $data['ppu']
                    ]
                )->id;
            }


            return $quotation_id;
        } catch (\Exception $e) {
            // Manejar la excepción según tus necesidades
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $quotationclient = Quotationclient::find($id);

        return $quotationclient;
    }

    public function update(Request $request, $id)
    {
        Quotationclient::find($id)->update($request->all());

        return;
    }

    public function destroy($id)
    {
        $quotationclient = Quotationclient::findOrFail($id);
        $quotationuser = QuotationUser::where('quotation_id', '=', $quotationclient->id)->first();

        if ($quotationuser === null) {
            $quotationclient->delete();
        } else {
            $quotationuser = QuotationUser::where('quotation_id', '=', $quotationclient->id)->firstOrFail();
            $quotationuservehicle = QuotationUserVehicle::where('user_id', '=', $quotationuser->id)->firstOrFail();
            $quotationuservehicle->delete();
            $quotationuser->delete();
            $quotationclient->delete();
        }

        return;
    }

    public function details($id)
    {
        return Quotationclient::findOrFail($id)->detailclients;
    }

    public function pdf($id)
    {
        try {
            $products = Quotationclient::findOrFail($id)->detailclients;
            $quotation = Quotationclient::find($id);

            $company = Company::where('user_id', '=', $quotation->user_id)->first();
            if ($company == null) {
                $company = $this->company_defect;
            }

            $client = Quotationclient::find($id)->client;
            $user = User::where('id', '=', $quotation->user_id)->first();

            $pdf = Pdf::loadView('pdf.quotationclient', compact(['company', 'quotation', 'client', 'products', 'user']));

            if ($quotation->user_id === 1) {
                return $pdf->stream('cotizacion N° ' . $quotation->id . '.pdf');
            } else {
                return $pdf->stream('cotizacion N° ' . $quotation->correlativo . '.pdf');
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function pdfIva($id)
    {
        try {
            $products = Quotationclient::findOrFail($id)->detailclients;
            $quotation = Quotationclient::find($id);

            $company = Company::where('user_id', '=', $quotation->user_id)->first();
            if ($company == null) {
                $company = $this->company_defect;
            }

            $client = Quotationclient::find($id)->client;
            $user = User::where('id', '=', $quotation->user_id)->first();

            $pdf = Pdf::loadView('pdf.quotationclientiva', compact(['company', 'quotation', 'client', 'products', 'user']));

            if ($quotation->user_id === 1) {
                return $pdf->stream('cotizacion N° ' . $quotation->id . '.pdf');
            } else {
                return $pdf->stream('cotizacion N° ' . $quotation->correlativo . '.pdf');
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function all()
    {

        $vehiclemodels = VehicleModel::select(DB::raw('vehicle_models.id as id,
                                                        vehicle_models.model as model,
                                                        vehicle_years.v_year as year,
                                                        vehicle_engines.v_engine as motor'))
            ->join('vehicle_years', 'vehicle_models.id', '=', 'vehicle_years.v_id')
            ->join('vehicle_engines', 'vehicle_years.id', '=', 'vehicle_engines.year_id')
            ->get();

        return $vehiclemodels;
    }



    public function forms()
    {
        $id = request('id');
        $quotationform = DB::table('quotation_users')
            ->join('quotationclients', 'quotation_users.quotation_id', '=', 'quotationclients.id')
            ->join('quotation_user_vehicles', 'quotation_users.id', '=', 'quotation_user_vehicles.user_id')
            ->select(
                'quotation_users.id',
                'quotation_users.name',
                'quotation_users.email',
                'quotation_users.phone',
                'quotation_user_vehicles.patentchasis',
                'quotation_user_vehicles.brand',
                'quotation_user_vehicles.model',
                'quotation_user_vehicles.year',
                'quotation_user_vehicles.engine',
                'quotation_user_vehicles.description',
                'quotation_users.created_at',
                'quotationclients.generado',
                'quotationclients.generado_client',
                'quotationclients.payment'
            )
            ->where('quotation_users.quotation_id', '=', $id)->get();
        return $quotationform;
    }


    public function correlativoQuotation()
    {
        $quotationclients = Quotationclient::get();

        $enumeradas = [];

        foreach ($quotationclients as $quotation) {
            $user_id = $quotation->user_id;

            if ($user_id !== 1) {
                if (!isset($enumeradas[$user_id])) {
                    $enumeradas[$user_id] = 0;
                }

                $enumeradas[$user_id]++;

                Quotationclient::where('id', '=', $quotation->id)->update([
                    'correlativo' => $enumeradas[$user_id]
                ]);
            }
        }

        return $quotationclients;
    }


    public function sparePart(Request $request){
        $quotationClient = Quotationclient::find($request->id_quotation)->update([
            'spare_parts' => $request->spare_parts
        ]);
        return $quotationClient;
    }

    
}
