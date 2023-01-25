<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DrawingContestSaveVoteRequest extends FormRequest
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
            'category_id' => [
                'required',
                'exists:drawing_contest_categories,id',
                Rule::unique('drawing_contest_votes')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
            ],
            'selected_picture_id' => [
                'required',
                'exists:drawing_contest_pictures,id',
                Rule::unique('drawing_contest_votes')->where(function ($query) {
                    return $query
                        ->where('user_id', auth()->user()->id)
                        ->where('category_id', request()->input('category_id'));
                })
            ],
            'campaign_stage' => 'required|integer'
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'categoria',
            'selected_picture_id' => 'imagem',
            'campaign_stage' => 'etapa',
        ];
    }

    public function messages()
    {
        return [
            'category_id.unique' => 'Você já votou nesta categoria.',
            'selected_picture_id.unique' => 'Você não pode votar em duas imagens da mesma categoria.',
        ];
    }
}
