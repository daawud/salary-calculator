<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollCalculateRequest extends FormRequest
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
            'employee_id' => 'required|integer|exists:employees,id',
            'salary' => 'required|numeric',
            'norm' => 'integer|between:1,31',
            'worked' => 'required|integer',
            'tax_deduction' => 'required|boolean',
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
            'is_pensioner' => 'required|boolean',
            'disability_group' => 'integer|between:1,3|nullable'
        ];
    }
}
