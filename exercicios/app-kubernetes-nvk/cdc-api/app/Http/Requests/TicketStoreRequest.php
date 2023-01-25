<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TicketStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // can only create entry for himself
        // cant strictly compare cause `user_id` might be a string
        return Auth::user()->id == $this->request->get('user_id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // should not create more than one coupon
        return [
            'user_id' => 'required|integer|exists:users,id|unique:tickets,user_id,'.$this->user()->id,
//            'user_id' => 'required|integer|exists:users,id',
            'city_id' => 'required|integer|exists:cities,id',
            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|digits_between:9,15',
            'address_street_name' => 'required|string',
            'address_number' => 'required|string',
            'address_neighbourhood' => 'required|string',
            'address_postal_code' => 'required|digits:8',
            'shipping_address_street_name' => 'required|string',
            'shipping_address_number' => 'required|string',
            'shipping_address_neighbourhood' => 'required|string',
            'shipping_address_postal_code' => 'required|digits:8',
            'shipping_address_complement' => 'nullable',
            'shipping_address_city_id' => 'required|integer|exists:cities,id',
            'shipping_address_recipient' => 'required|string',
            'shipping_address_recipient_kinship' => 'required|string',
            'suggestion' => 'required|string|max:500'
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
            'name' => 'nome',
            'email' => 'e-mail',
            'phone' => 'número de telefone',
            'address_street_name' => 'endereço atual',
            'address_number' => 'número atual',
            'address_neighbourhood' => 'bairro atual',
            'address_postal_code' => 'CEP atual',
            'shipping_address_street_name' => 'endereço para entrega',
            'shipping_address_number' => 'número para entrega',
            'shipping_address_neighbourhood' => 'bairro',
            'shipping_address_postal_code' => 'CEP para entrega',
            'shipping_address_complement' => 'complemento',
            'shipping_address_recipient' => 'destinatário',
            'shipping_address_recipient_kinship' => 'grau de parentesco',
            'suggestion' => 'sugestão'
        ];
    }
}
