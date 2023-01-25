<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    protected $table = 'quiz_questions_options';

    protected $fillable = [
        'question_id',
        'option',
        'is_right_option',
    ];

    public function question() {
        return $this->belongsToMany(Question::class, 'question_id');
    }
}
