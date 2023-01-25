<?php

namespace App\Http\Requests\Mail;

use Illuminate\Foundation\Http\FormRequest;

class VideocastSuggestionRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'suggestion' => 'required|string|max:255'
        ];
    }
}
