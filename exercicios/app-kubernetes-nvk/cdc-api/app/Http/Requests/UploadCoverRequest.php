<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UploadCoverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user())
            return true;
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cover' => 'required|max:2048|mimes:jpeg,bmp,png'
        ];
    }

    public function messages()
    {
        return [
            'cover.required' => 'O arquivo é obrigatório.',
            'cover.max' => 'A imagem é muito grande (máx. :max kb).',
            'cover.mimes' => 'Tipos válidos de arquivos: :values.',
        ];
    }
}
