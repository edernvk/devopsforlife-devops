<?php

namespace App;

use App\Enums\StatusQuizEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Quiz extends Model
{
    protected $table = 'quiz';

    protected $fillable = [
        'status'
    ];

    protected $casts = [
        'status' => 'number'
    ];

    public function questions() {
        return $this->hasMany(QuizQuestion::class, 'quiz_id');
    }

    public function active()
    {
        return $this->update([
            'status' => StatusQuizEnum::ACTIVE,
        ]);
    }

    public static function publishRules()
    {

        return [
            'name' => ['required','string'],
            'quiz_description' => ['required','string'],
            'slug' => ['required','string'],
            'initial_date' => ['required'],
            'final_date' => ['required'],
            'questions' => ["required", "array","min:1"],
            'questions.*.options' => ["required","array","min:2"],
        ];
    }
}
