<?php

namespace App\Services;

use App\Enums\StatusQuizEnum;
use App\Http\Requests\QuizPublishRequest;
use App\Quiz;
use App\QuizOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizService
{
    public function saveOptions($request, $question)
    {

        $options = $request->options;
        $data = [];

        foreach($options as $option) {
            $data[] = [
                'question_id' => $question->id,
                'option' => $option['option'],
                'is_right_option' => $option['is_right']
            ];
        }
        $isSaved = QuizOption::insert($data);

        return $isSaved;
    }

    public function validateQuestion($quiz){
        try {
            $quizPublishRequest = new QuizPublishRequest();
            $validator = Validator::make($quiz->toArray(), $quizPublishRequest::rules(), $quizPublishRequest->messages());
            if(!$validator->fails()){
                $quiz->active();
                return $quiz;
            }
            $errors = $validator->messages()->all();

            return $errors;
        return $validator;

        } catch (\Illuminate\Validation\ValidationException $e ) {
            $errors = $e->errors();
            return \response($errors,400);
        }
    }

}
