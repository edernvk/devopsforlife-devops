<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TicketUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
}
