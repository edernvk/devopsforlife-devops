<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'cpf' => 'required|string|unique:users,cpf',
            'registration' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'mobile' => 'required|string|max:15',
            'city_id' => 'nullable|integer',
            'team_id' => 'nullable|integer',
            'avatar' => 'nullable|string'
        ];
    }
}
