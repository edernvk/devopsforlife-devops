<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChristmasBasketStoreRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->user()->id
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
            'user_id' => 'required|integer|exists:users,id|unique:christmas_baskets,user_id',
//            'user_id' => 'required|exists:users,id',
            'shipping_address_zipcode' => 'required|string|min:8',
            'shipping_address_street_name' => 'required|string',
            'shipping_address_number' => 'required|string',
            'shipping_address_neighbourhood' => 'required|string',
            'shipping_address_city' => 'required|string',
            'shipping_address_complement' => 'nullable|string',
            'name_recipient' => 'required|string',
            'degree_kinship' => 'required|string',
            'suggestion' => 'nullable|string|max:500'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'shipping_address_street_name' => 'endereço para entrega',
            'shipping_address_number' => 'número para entrega',
            'shipping_address_neighbourhood' => 'bairro',
            'shipping_address_zipcode' => 'CEP para entrega',
            'shipping_address_city' => 'cidade',
            'shipping_address_complement' => 'complemento',
            'name_recipient' => 'nome do destinatário',
            'degree_kinship' => 'grau de parentesco',
            'suggestion' => 'sugestão'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'user_id.unique' => 'Este usuário já respondeu este formulário.',
        ];
    }
}
