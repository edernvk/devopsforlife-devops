<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'path' => 'required|file',
            'accepted' => 'required|boolean',
            'deadline' => 'required|date'
        ];
    }
}
