<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExtensionDivisionUpdateRequest extends FormRequest
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
            'color' => 'nullable|string',
            'areas' => 'sometimes|array',
            'areas.*.id' => 'required|integer|exists:extension_areas,id'
        ];
    }
}
