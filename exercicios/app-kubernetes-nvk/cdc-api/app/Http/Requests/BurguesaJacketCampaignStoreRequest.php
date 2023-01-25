<?php

namespace App\Http\Requests;

use App\BurguesaJacketCampaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BurguesaJacketCampaignStoreRequest extends FormRequest
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
            'jacket_1_size' => [
                'required',
                'string',
                Rule::in(BurguesaJacketCampaign::JACKET_SIZES)
            ],
            'jacket_2_size' => [
                'nullable',
                'string',
                Rule::in(BurguesaJacketCampaign::JACKET_SIZES)
            ],
            'installments_amount' => [
                'required',
                'numeric',
                'between:1,'.BurguesaJacketCampaign::MAX_INSTALLMENTS
            ],
            'payment_agreement' => [
                'required',
                'boolean',
                'accepted'
            ],
            'user_id' => [
                'required',
                'unique:App\BurguesaJacketCampaign,user_id'
            ]
        ];
    }
}
