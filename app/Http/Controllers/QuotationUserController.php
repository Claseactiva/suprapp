<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\QuotationUser;
use App\Models\QuotationUserVehicle;
use App\Http\Requests\StoreQuotationUser;
use App\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use App\Notifications\EmailNotificator;
use App\Models\Quotationclient;
use Illuminate\Support\Facades\Auth;

class QuotationUserController extends Controller
{
    use Notifiable;

    public function cotizar(){
        return view('quotation');
    }

    public function store(StoreQuotationUser $request){
        $validate = $request->validated();
        $data = $request->all();

        $name         = $data['name'];
        $email        = $data['email'];
        $phone        = $data['phone'];
        $patentchasis = $data['patentchasis'];
        $brand        = $data['brand'];
        $model        = $data['model'];
        $year         = $data['year'];
        $engine       = $data['engine'];
        $description  = $data['description'];
        $user_id_logeado = Auth::id();


        $clients = Client::where('user_id', '=', $user_id_logeado)->where('type', '=', 'Cliente Particular')->get();
        foreach ($clients as $client) {
            
            $quotation_id = Quotationclient::create(
            [
                'user_id' => $user_id_logeado, //usuario alvaro por defecto
                'client_id' => $client->id, //usuario particular
                'state' => 'Pendiente',
                'payment' => 'Contado',
                'client_text' => $name,
                'vehicle' => $brand.' '.$model.' '.$year.' '.$engine,
                'generado' => 3
            ])->id;
        

            $user_id = QuotationUser::create(
                [ 
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'quotation_id' => $quotation_id
                ]
            )->id;

            $quotation = QuotationUserVehicle::create(
                [ 
                    'patentchasis' => $patentchasis,
                    'user_id' => $user_id,
                    'brand' => $brand,
                    'model' => $model,
                    'year' => $year,
                    'engine' => $engine,
                    'email' => $email,
                    'description' => $description
                ]
            )->id;

            
        }

        if(isset($quotation)){
            $user = new User();
            $user->email = 'comercialsupra4@gmail.com';
            $user->notify(new EmailNotificator($name, $email, $phone, $patentchasis, $description));
        }
        
        return response()->json(
        [
            'valid'=> true,
            'data' => [ 'message' => 'Cotizacion ingresada correctamente!' ]
        ], 200);
    }

    public function show($id){
        return QuotationUser::findOrFail($id)->email;
    }

    public function storeMechanic(Request $request){

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
            
            $quotation_id = Quotationclient::create(
            [
                'user_id' => $user_id_logeado, //usuario alvaro por defecto
                'client_id' => $client->id, //usuario particular
                'state' => 'Pendiente',
                'payment' => 'Contado',
                'client_text' => $name,
                'vehicle' => $brand.' '.$model.' '.$year.' '.$engine,
                'generado' => 4,
                'tipo_detalle' => 1
            ])->id;

            $user_id = QuotationUser::create(
                [ 
                    'name' => $name,
                    'email' => $email,
                    
                    'quotation_id' => $quotation_id
                ]
            )->id;

            $quotation = QuotationUserVehicle::create(
                [ 
                    'patentchasis' => $patentchasis,
                    'user_id' => $user_id,
                    'brand' => $brand,
                    'model' => $model,
                    'year' => $year,
                    'engine' => $engine,
                    'email' => $email,
                    'description' => $description
                ]
            )->id;
        }

        if(isset($quotation)){
            $user = new User();
            $user->email = 'comercialsupra4@gmail.com';
            $user->notify(new EmailNotificator($name, $email, $phone, $patentchasis, $description));
        }


        return response()->json([
            'valid'=> true,
            'data' => [
                'message' => 'Cotizacion ingresada correctamente!'
            ]
        ], 200);
    }


    public function storeUserExpress(Request $request){

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
            
            $quotation_id = Quotationclient::create(
            [
                'user_id' => $user_id_logeado, //usuario alvaro por defecto
                'client_id' => $client->id, //usuario particular
                'state' => 'Pendiente',
                'payment' => 'Contado',
                'client_text' => $name,
                'vehicle' => $brand.' '.$model.' '.$year.' '.$engine,
                'generado' => 5
            ])->id;

            $user_id = QuotationUser::create(
                [ 
                    'name' => $name,
                    'email' => $email,
                    'quotation_id' => $quotation_id
                ]
            )->id;

            $quotation = QuotationUserVehicle::create(
                [ 
                    'patentchasis' => $patentchasis,
                    'user_id' => $user_id,
                    'brand' => $brand,
                    'model' => $model,
                    'year' => $year,
                    'engine' => $engine,
                    'email' => $email,
                    'description' => $description
                ]
            )->id;
        }

        if(isset($quotation)){
            $user = new User();
            $user->email = 'comercialsupra4@gmail.com';
            $user->notify(new EmailNotificator($name, $email, $phone, $patentchasis, $description));
        }


        return response()->json([
            'valid'=> true,
            'data' => [
                'message' => 'Cotizacion express ingresada correctamente!'
            ]
        ], 200);
    }



}
