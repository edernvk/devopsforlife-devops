<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizPublishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'name' => ['required','string'],
            'quiz_description' => ['required','string'],
            'slug' => ['required','string'],
            'initial_date' => ['required'],
            'final_date' => ['required'],
            'questions' => ["required", "array","min:2"],
            'questions.*.options' => ["required","array","min:2"],
        ];
    }

    public static function publishRules()
    {

        return [
            'name' => ['required','string'],
            'quiz_description' => ['required','string'],
            'slug' => ['required','string'],
            'initial_date' => ['required', 'min:'.gmdate("Y-m-d\TH:i:s\Z")],
            'final_date' => ['required','min:'.gmdate("Y-m-d\TH:i:s\Z")],
            'questions' => ["required", "array","min:2"],
            'questions.*.options' => ["required","array","min:2"],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'quiz_description.required' => 'A descrição é obrigatória.',
            'slug.required' => 'O slug é obrigatório',
            'initial_date.min' => 'A data inicial é obrigatória',
            'final_date.min' => 'A data final é obrigatória',
            'questions.min' => 'O tamanho das questões deve ser maior do que 2 questões',
            'questions.required' => 'O tamanho das questões deve ser maior do que 2 questões',
            'questions.*.options.min' => 'Cada questão deve ter ao menos 2 opções',
            'questions.*.options.required' => 'Cada questão deve ter ao menos 2 opções',
        ];
    }
}
