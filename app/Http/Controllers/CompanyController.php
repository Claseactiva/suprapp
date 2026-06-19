<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;

class CompanyController extends Controller
{

    public function index()
    {
        $user_id = Auth::id();
        $company = Company::where('user_id', '=', $user_id)->first();

        return $company;
    }


    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'rut' => 'required',
                'razonSocial' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'comuna' => 'required',
                'giro' => 'required',
            ], [
                'email.required' => 'El campo correo electrónico es obligatorio',
                'rut.required' => 'El campo rut es obligatorio',
                'phone.required' => 'El campo telefono es obligatorio',
                'razonSocial.required' => 'El campo razon social es obligatorio',
                'address.required' => 'El campo direccion es obligatorio',
                'comuna.required' => 'El campo comuna es obligatorio',
                'giro.required' => 'El campo giro es obligatorio',
            ]);

            $company = Company::create($request->all());
            return $company;
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'rut' => 'required',
                'razonSocial' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'comuna' => 'required',
                'giro' => 'required',
            ], [
                'email.required' => 'El campo correo electrónico es obligatorio',
                'rut.required' => 'El campo rut es obligatorio',
                'phone.required' => 'El campo telefono es obligatorio',
                'razonSocial.required' => 'El campo razon social es obligatorio',
                'address.required' => 'El campo direccion es obligatorio',
                'comuna.required' => 'El campo comuna es obligatorio',
                'giro.required' => 'El campo giro es obligatorio',
            ]);

            $company = Company::find($id)->update($request->all());
            return $company;
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function updateUserLogo(Request $request)
    {
        try {
            $user_id = Auth::id();
            $arreglo = array();
            $uploadedFile = $request->logo;

            $manager = new ImageManager(array('local' => 'imagick'));
            $this->deleteImage($user_id);

            foreach ($uploadedFile as $file) {

                $filename = time() . '.' . $file->getClientOriginalExtension();
                $path = storage_path('app/public/images/logos/' . $filename);

                $img = $manager->make($file->getRealPath());
                $img->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);

                $url = '/images/logos/' . $filename;

                $findCompany = Company::where('user_id', '=', $user_id)->first();
                $findCompany->update(['url' => $url]);

                array_push($arreglo, $path);
            }
            return response($arreglo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function deleteImage($user_id)
    {
        try {
            $company = Company::where('user_id', '=', $user_id)->first();
            // $rutaImagen = public_path() . $company->url;
            $rutaImagen = getcwd() . $company->url;

            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
                $company = Company::where('user_id', '=', $user_id)->update(['url' => '']);
            }

            return;
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }
}
