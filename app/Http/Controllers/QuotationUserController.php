<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuotationUser;
use App\Models\Client;
use App\Models\Quotationclient;
use App\Models\QuotationUser;
use App\Models\QuotationUserVehicle;
use App\Notifications\EmailNotificator;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class QuotationUserController extends Controller
{
    use Notifiable;

    public function cotizar()
    {
        return view('quotation');
    }

    public function cotizar_express()
    {
        return view('quotation_express');
    }

    public function store(StoreQuotationUser $request)
    {
        $data = $request->validated();

        [$ownerUserId, $client] = $this->resolvePublicQuotationContext();

        $name = trim($data['name']);
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone']);
        $patentchasis = trim($data['patentchasis'] ?? '');
        $brand = trim($data['brand']);
        $model = trim($data['model']);
        $year = trim($data['year']);
        $engine = trim($data['engine'] ?? '');
        $description = trim($data['description']);

        $this->createPublicQuotation(
            $ownerUserId,
            $client,
            3,
            $name,
            $email,
            $phone,
            $patentchasis,
            $brand,
            $model,
            $year,
            $engine,
            $description
        );

        $this->notifyPublicQuotationAfterResponse($name, $email, $phone, $patentchasis, $description);

        return response()->json([
            'valid' => true,
            'data' => ['message' => 'Cotizacion ingresada correctamente!']
        ], 200);
    }

    public function show($id)
    {
        return QuotationUser::findOrFail($id)->email;
    }

    public function storeMechanic(Request $request)
    {
        $data = $request->all();

        $name = Auth::user()->name;
        $email = Auth::user()->email;
        $user_id_logeado = Auth::id();

        $patentchasis = $data['patentchasis'];
        $brand = $data['brand'];
        $model = $data['model'];
        $year = $data['year'];
        $engine = $data['engine'];
        $description = $data['description'];
        $phone = '';

        $clients = Client::where('user_id', '=', Auth::id())->where('type', '=', 'Cliente Particular')->get();
        foreach ($clients as $client) {
            $quotation_id = Quotationclient::create([
                'user_id' => $user_id_logeado,
                'client_id' => $client->id,
                'state' => 'Pendiente',
                'payment' => 'Contado',
                'client_text' => $name,
                'vehicle' => $this->buildVehicleText($brand, $model, $year, $engine),
                'generado' => 4,
                'tipo_detalle' => 1
            ])->id;

            $user_id = QuotationUser::create([
                'name' => $name,
                'email' => $email,
                'quotation_id' => $quotation_id
            ])->id;

            $quotation = QuotationUserVehicle::create([
                'patentchasis' => $patentchasis,
                'user_id' => $user_id,
                'brand' => $brand,
                'model' => $model,
                'year' => $year,
                'engine' => $engine,
                'email' => $email,
                'description' => $description
            ])->id;
        }

        if (isset($quotation)) {
            $this->notifyPublicQuotationAfterResponse($name, $email, $phone, $patentchasis, $description);
        }

        return response()->json([
            'valid' => true,
            'data' => [
                'message' => 'Cotizacion ingresada correctamente!'
            ]
        ], 200);
    }

    public function storeUserExpress(Request $request)
    {
        $data = $request->validate([
            'patentchasis' => 'nullable|min:6|max:190',
            'brand' => 'required',
            'model' => 'required',
            'year' => 'required',
            'engine' => 'nullable|max:190',
            'description' => 'required|min:6',
            'name' => 'nullable|min:3|max:190',
            'email' => 'nullable|email',
            'phone' => 'nullable|min:7',
        ]);

        [$ownerUserId, $client] = $this->resolvePublicQuotationContext();

        $name = trim($data['name'] ?? 'Cotizacion Express Web');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $patentchasis = trim($data['patentchasis'] ?? '');
        $brand = trim($data['brand']);
        $model = trim($data['model']);
        $year = trim($data['year']);
        $engine = trim($data['engine'] ?? '');
        $description = trim($data['description']);

        $this->createPublicQuotation(
            $ownerUserId,
            $client,
            5,
            $name,
            $email,
            $phone,
            $patentchasis,
            $brand,
            $model,
            $year,
            $engine,
            $description
        );

        $this->notifyPublicQuotationAfterResponse($name, $email, $phone, $patentchasis, $description);

        return response()->json([
            'valid' => true,
            'data' => [
                'message' => 'Cotizacion express ingresada correctamente!'
            ]
        ], 200);
    }

    private function createPublicQuotation($ownerUserId, Client $client, $generado, $name, $email, $phone, $patentchasis, $brand, $model, $year, $engine, $description)
    {
        return DB::transaction(function () use ($ownerUserId, $client, $generado, $name, $email, $phone, $patentchasis, $brand, $model, $year, $engine, $description) {
            $quotationId = Quotationclient::create([
                'user_id' => $ownerUserId,
                'client_id' => $client->id,
                'state' => 'Pendiente',
                'payment' => 'Contado',
                'client_text' => $name,
                'vehicle' => $this->buildVehicleText($brand, $model, $year, $engine),
                'generado' => $generado,
            ])->id;

            $quotationUserId = QuotationUser::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'quotation_id' => $quotationId
            ])->id;

            QuotationUserVehicle::create([
                'patentchasis' => $patentchasis,
                'user_id' => $quotationUserId,
                'brand' => $brand,
                'model' => $model,
                'year' => $year,
                'engine' => $engine,
                'email' => $email,
                'description' => $description
            ]);

            return $quotationId;
        });
    }

    private function resolvePublicQuotationContext($includeOwnerUser = false)
    {
        $ownerUserId = (int) env('PUBLIC_QUOTATION_OWNER_ID', 1);
        if ($ownerUserId <= 0) {
            $ownerUserId = 1;
        }

        $client = Client::where('user_id', $ownerUserId)
            ->where('type', 'Cliente Particular')
            ->orderBy('id')
            ->first();

        if (!$client) {
            $client = Client::where('type', 'Cliente Particular')->orderBy('id')->first();
            if ($client && $client->user_id) {
                $ownerUserId = (int) $client->user_id;
            }
        }

        $ownerUser = User::find($ownerUserId);

        if (!$client || !$ownerUser) {
            throw ValidationException::withMessages([
                'form' => ['No existe un cliente base configurado para las cotizaciones publicas.'],
            ]);
        }

        if ($includeOwnerUser) {
            return [$ownerUserId, $client, $ownerUser];
        }

        return [$ownerUserId, $client];
    }

    private function buildVehicleText($brand, $model, $year, $engine)
    {
        return trim(implode(' ', array_filter([
            trim((string) $brand),
            trim((string) $model),
            trim((string) $year),
            trim((string) $engine),
        ])));
    }
    private function notifyPublicQuotationAfterResponse($name, $email, $phone, $patentchasis, $description)
    {
        dispatch(function () use ($name, $email, $phone, $patentchasis, $description) {
            try {
                Notification::route('mail', 'comercialsupra4@gmail.com')
                    ->notify(new EmailNotificator($name, $email, $phone, $patentchasis, $description));
            } catch (\Throwable $exception) {
                Log::warning('Public quotation notification failed', [
                    'target_email' => 'comercialsupra4@gmail.com',
                    'quotation_name' => $name,
                    'patentchasis' => $patentchasis,
                    'error' => $exception->getMessage(),
                ]);
            }
        })->afterResponse();
    }
}

