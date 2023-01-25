<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CupShirtCampaingSaveRequest extends FormRequest
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
        // dd($this->all());
        return [
            'total_amount' => 'required|integer',
            'installments_amount' => 'required|integer',
            'payment_agreement' => 'required|string',
            'products' => 'array|required',
            'products.*.id' => 'required|integer',
            'products.*.amount' => 'required|integer',
            'products.*.size' => 'required|string',
        ];
    }
}
