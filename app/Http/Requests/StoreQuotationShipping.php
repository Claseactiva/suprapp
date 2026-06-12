<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationShipping extends FormRequest
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
            'nombre' => 'nombre',
            'rut' => 'rut',
            'telefono' => 'telefono',
            'ciudad' => 'ciudad',
            'direccion' => 'direccion'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'nombre' => 'required',
            'rut' => 'required',
            'telefono' => 'required',
            'ciudad' => 'required'
        ];
    }

    public function messages(){
        return [
            'nombre.required' => 'El campo nombre es obligatorio',
            'rut.required' => 'El campo rut es obligatorio',
            'telefono.required' => 'El campo teléfono es obligatorio',
            'ciudad.required' => 'Selecione una ciudad'
       ];
    }
}
