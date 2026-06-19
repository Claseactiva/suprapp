<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\VehicleYear;
use Illuminate\Support\Facades\DB;

class YearRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'v_year' => ['required','max:4','min:4',

            function ($attribute, $value, $fail) {
                $years = DB::table('vehicle_years')->where([
                    ['v_id', '=', $value->v_id],
                    ['v_year', '=', $value->v_year]])
                    ->get();
                if (!empty($years)) {
                    $fail(__('El modelo y año ya existe'));
                }
            }],
        ];
    }

    public function messages()
    {
        return [
            'v_year.required' => 'El campo año es obligatorio',
            'v_year.min' => 'El campo año debe tener al menos 4 caracteres',
            'v_year.max' => 'El campo año debe tener a lo más 4 caracteres'
        ];
    }
}
