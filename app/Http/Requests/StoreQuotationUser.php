<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function attributes()
{
    return [
        'name' => 'nombre',
        'email' => 'correo',
        'phone' => 'teléfono',
        'patentchasis' => 'patente o chasis',
        'brand' => 'marca',
        'tipo_vehiculo' => 'tipo_vehiculo',
        'model' => 'modelo',
        'year' => 'año',
        'engine' => 'motor',
        'description' => 'repuestos a solicitar'
    ];
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'name' => 'required|min:3|max:190|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email',
            'phone' => 'required|min:6',
            'patentchasis' => 'required|min:6|max:190',
            'brand' => 'required',
            'model' => 'required',
            'year' => 'required',
            'engine' => 'required',
            'description' => 'required|min:6'
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min' => 'El campo nombre debe tener al menos 3 caracteres',
            'name.max' => 'El campo nombre debe tener a lo más 190 caracteres',
            'name.regex' => 'El campo nombre sólo puede tener letras y espacios',
            'email.required' => 'El campo correo es obligatorio',
            'phone.required' => 'El campo teléfono es obligatorio',
            'phone.min' => 'El campo teléfono debe tener al menos 6 números',
            'patentchasis.required' => 'El campo patente o chasis es obligatorio',
            'patentchasis.min' => 'El campo patente o chasis debe tener al menos 6 caracteres',
            'patentchasis.max' => 'El campo patente o chasis debe tener a lo más 190 caracteres',
            'brand.required' => 'El campo marca es obligatorio',
            'model.required' => 'El campo modelo es obligatorio',
            'year.required'=> 'El campo año es obligatorio',
            'engine.required' => 'El campo motor es obligatorio',
            'description.required' => 'El campo repuestos a solicitar es obligatorio',
            'description.min' => 'El campo repuestos a solicitar debe tener al menos 6 caracteres'
       ];
    }
}
