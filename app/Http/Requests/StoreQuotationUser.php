<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationUser extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
            'phone' => 'telefono',
            'patentchasis' => 'patente o chasis',
            'brand' => 'marca',
            'tipo_vehiculo' => 'tipo_vehiculo',
            'model' => 'modelo',
            'year' => 'ano',
            'description' => 'repuestos a solicitar'
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3|max:190|regex:/^[\pL\s\-]+$/u',
            'phone' => 'required|min:6',
            'patentchasis' => 'required|min:6|max:190',
            'brand' => 'required',
            'model' => 'required',
            'year' => 'required',
            'engine' => 'nullable|max:190',
            'email' => 'nullable|email',
            'description' => 'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'name.min' => 'El campo nombre debe tener al menos 3 caracteres',
            'name.max' => 'El campo nombre debe tener a lo mas 190 caracteres',
            'name.regex' => 'El campo nombre solo puede tener letras y espacios',
            'phone.required' => 'El campo telefono es obligatorio',
            'phone.min' => 'El campo telefono debe tener al menos 6 numeros',
            'patentchasis.required' => 'El campo patente o chasis es obligatorio',
            'patentchasis.min' => 'El campo patente o chasis debe tener al menos 6 caracteres',
            'patentchasis.max' => 'El campo patente o chasis debe tener a lo mas 190 caracteres',
            'brand.required' => 'El campo marca es obligatorio',
            'model.required' => 'El campo modelo es obligatorio',
            'year.required'=> 'El campo ano es obligatorio',
            'description.required' => 'El campo repuestos a solicitar es obligatorio',
            'description.min' => 'El campo repuestos a solicitar debe tener al menos 6 caracteres'
       ];
    }
}
