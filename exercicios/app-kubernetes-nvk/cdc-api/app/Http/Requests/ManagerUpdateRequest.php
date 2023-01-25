<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerUpdateRequest extends FormRequest
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
            'name' => 'string',
            'type' => 'string',
            'email' => 'email',
            'cities' => 'array|sometimes',
            'cities.*.id' => 'nullable|integer|exists:cities,id',
        ];
    }
}
