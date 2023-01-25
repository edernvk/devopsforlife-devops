<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PaycheckAccessStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        Log::info('validou store paycheck access');
        return auth()->user()->hasRole('Administrador');
    }

    protected function prepareForValidation()
    {
        // maybe clear and normalize cpf?
        // this cpf field comes from registered users, so it should be fine to normalize them
        // clear = leave only numbers
        // normalize = 11 digits with left pad 0
        return $this->merge([
            'cpf'=> $this->route('user')->cpf,
            'user_id' => $this->route('user')->id
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
            'email' => 'required|email',
            'password' => 'required|string',
            'cpf' => 'required|string|max:11', // LEAVE MAX:11 TO CATCH IRREGULAR USERS
            'user_id' => 'required|integer|exists:users,id'
        ];
    }
}
