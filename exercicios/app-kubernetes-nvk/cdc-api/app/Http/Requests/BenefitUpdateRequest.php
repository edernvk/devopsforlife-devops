<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BenefitUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'partner' => 'required|string',
            'contact' => 'required|string',
            'benefit' => 'required|string',
            'division_id' => 'required|integer|exists:benefit_divisions,id',
            'area_id' => 'sometimes|nullable|integer|exists:benefit_areas,id'
        ];
    }

}
