<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UploadPosterRequest extends FormRequest
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
            'poster' => 'required|max:2048|mimes:jpeg,bmp,png'
        ];
    }

    public function messages()
    {
        return [
            'poster.required' => 'O arquivo é obrigatório.',
            'poster.max' => 'A imagem é muito grande (máx. :max kb).',
            'poster.mimes' => 'Tipos válidos de arquivos: :values.',
        ];
    }
}
