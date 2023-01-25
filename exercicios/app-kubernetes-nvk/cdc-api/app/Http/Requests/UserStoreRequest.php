<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 2020-11-12
        // only admins should create new users
        return Auth::check() && request()->user()->authorizeRoles(['Administrador']);
    }

    protected function prepareForValidation()
    {
        //Remove any character that isn't A-Z, a-z or 0-9.
        $mobileClean = preg_replace("/[^A-Za-z0-9]/", '', $this->get('mobile'));
        return $this->merge([
            'mobile'=> $mobileClean
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
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'cpf' => 'required|string|unique:users,cpf',
            'birth_date' => 'nullable|string',
            'registration' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'mobile' => 'required|string|min:10|max:11',
            'city_id' => 'required|nullable|integer',
            'team_id' => 'nullable|integer',
            'avatar' => 'nullable|string',
            'roles' => 'required|array',
            'roles.*' => 'integer|exists:roles,id',
            'vcard_enable' => 'nullable|boolean'
        ];
    }
}
