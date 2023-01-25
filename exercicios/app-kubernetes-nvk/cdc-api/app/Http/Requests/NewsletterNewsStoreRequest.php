<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsletterNewsStoreRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->user()->id
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
            'user_id' => 'required|integer|exists:users,id',
            'title' => 'required|string',
            'content' => 'required|string',
            'contrast' => 'boolean',
            'status_id' => 'required|integer',
            'publish_datetime' => 'nullable|date',
            'thumbnail' => 'required|string',
            'commentable' => 'boolean',
            'thumbnail' => 'required|string'
        ];
    }
}
