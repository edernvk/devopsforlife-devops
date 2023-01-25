<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExtensionNumberUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            'number' => 'required|string',
            'division_id' => 'required|integer|exists:extension_divisions,id',
            'area_id' => 'sometimes|nullable|integer|exists:extension_areas,id'
        ];
    }

}
