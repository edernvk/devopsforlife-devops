<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileStoreRequest extends FormRequest
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
            'user_id' => auth()->id()
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
            'name' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'path' => 'required|string',
            'accepted' => 'required',
            'deadline' => 'nullable',
            'to' => 'sometimes|array',
            'to.*.id' => 'integer',
            'folders' => 'nullable|array',
            // 'folders.*.id' => 'required|integer',
        ];
    }
}
