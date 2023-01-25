<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PushSendUsersRequest extends FormRequest
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
            'title' => 'required|string|max:30',
            'message' => 'required|string|max:170',
            'url' => 'nullable|string',
            'delivered' => 'required|string',
            'cpfs' => 'array',
            'publish_datetime' => 'nullable'
        ];
    }
}
