<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BenefitAreaBulkStoreRequest extends FormRequest
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
            'areas.*.name' => 'required|string',
            'areas.*.benefit_division_id' => 'sometimes|nullable|integer|exists:benefit_divisions,id',
        ];
    }
}
