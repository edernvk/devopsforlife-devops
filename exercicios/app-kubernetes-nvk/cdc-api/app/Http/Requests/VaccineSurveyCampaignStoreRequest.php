<?php

namespace App\Http\Requests;

use App\VaccineSurveyCampaign;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Factory as ValidationFactory;

class VaccineSurveyCampaignStoreRequest extends FormRequest
{

    public function __construct(ValidationFactory $validationFactory)
    {
        $validationFactory->extend(
            'accepted_if',
            function ($attribute, $value, $parameters, $validator) {
                $acceptable = ['yes', 'on', '1', 1, true, 'true'];

                $other = Arr::get($validator->getData(), $parameters[0]);

                $values = array_slice($parameters, 1);

                if (in_array('boolean', Arr::get($validator->getRules(), $parameters[0], [])) || is_bool($other)) {
                    $values = array_map(function ($value) {
                        if ($value === 'true') {
                            return true;
                        } elseif ($value === 'false') {
                            return false;
                        }

                        return $value;
                    }, $values);
                }

                if (is_null($other)) {
                    $values = array_map(function ($value) {
                        return Str::lower($value) === 'null' ? null : $value;
                    }, $values);
                }

                if (in_array($other, $values, is_bool($other) || is_null($other))) {
                    return $this->validateRequired($attribute, $value) && in_array($value, $acceptable, true);
                }

                return true;
            },
            'O campo primeira_dose deve ser "sim" quando segunda_dose é "sim" ou "não necessária".'
        );

    }

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
//        1 dose yes, 2 dose yes
//        1 dose yes, 2 dose no-
//        1 dose yes, 2 dose n/a
//
//        1 dose no-, 2 dose yes ~ (1 dose must be yes if 2 dose yes)
//        1 dose no-, 2 dose no-
//        1 dose no-, 2 dose n/a ~ (1 dose must be yes if 2 dose n/a)
        return [
            'local_age_reached' => [
                'required',
                'string',
                Rule::in(VaccineSurveyCampaign::BOOLEAN_OPTIONS)
            ],
            'first_dose' => [
                'required',
                'string',
                Rule::in(VaccineSurveyCampaign::BOOLEAN_OPTIONS),
                'accepted_if:second_dose,"yes","n/a"'
            ],
            'second_dose' => [
                'required',
                'string',
                Rule::in(VaccineSurveyCampaign::BOOLEAN_OPTIONS_EXTRA)
            ],
            'user_id' => [
                'required',
                'unique:App\VaccineSurveyCampaign,user_id'
            ]
        ];
    }

    protected function validateRequired($attribute, $value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1) {
            return false;
        }

        return true;
    }
}
