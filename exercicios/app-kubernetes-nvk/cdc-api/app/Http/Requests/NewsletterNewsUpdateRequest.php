<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterNewsUpdateRequest extends FormRequest
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
            'title' => 'string',
            'content' => 'string',
            'contrast' => 'boolean',
            'thumbnail' => 'string',
            'status_id' => 'integer',
            'publish_datetime' => 'nullable|date',
            'commentable' => 'boolean',
        ];
    }
}
