<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DrawingContestSaveBatchVotesRequest extends FormRequest
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
            'votes.*.category_id' => [
                'required',
                'exists:drawing_contest_categories,id',
                Rule::unique('drawing_contest_votes')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id)
                                ->where('campaign_stage', $this->input('campaign_stage'));
                })
            ],
            'votes.*.selected_picture_id' => 'required|exists:drawing_contest_pictures,id',
            'votes.*.campaign_stage' => 'required|integer'
        ];
    }

    public function attributes()
    {
        return [
            'votes.*.category_id' => 'categoria',
            'votes.*.selected_picture_id' => 'imagem',
            'votes.*.campaign_stage' => 'etapa',
        ];
    }

    public function messages()
    {
        return [
            'votes.*.category_id.unique' => 'Você já votou nesta categoria.',
        ];
    }

}
