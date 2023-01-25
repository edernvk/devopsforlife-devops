<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductNotFoundStoreRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->id()
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'establishment_name' => 'required|string',
            'establishment_address' => 'required|string',
            'city_id' => 'required|integer|exists:cities,id',
            'state_id' => 'required|integer|exists:states,id',
            'user_id' => 'required|integer|exists:users,id',
            'products' => 'required|array'
        ];
    }
}
