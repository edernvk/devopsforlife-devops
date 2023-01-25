<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';

    protected $fillable = [
        'quiz_id',
        'question',
        'type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function options() {
        return $this->hasMany(QuizOption::class, 'question_id');
    }

    public function quiz() {
        return $this->belongsToMany(Quiz::class, 'quiz_id');
    }
}
