<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class VideocastUpdateRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'video_url' => 'required|url',
            'date' => 'required|date_format:d/m/Y - H:i \h\r\s',
            'trackeable' => 'required|boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'date.date_format' => 'O campo de data não combina com o formato "DD/MM/AAAA - hh:mm hrs"'
        ];
    }
}
