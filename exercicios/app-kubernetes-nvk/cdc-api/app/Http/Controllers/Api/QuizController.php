<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatusQuizEnum;
use App\Quiz;
use App\QuizOption;
use App\QuizQuestion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuizUpdateRequest;
use App\Services\QuizService;
use App\Http\Services\QuizAnswerService;
use Carbon\Carbon;

class QuizController extends Controller
{

    public function __construct(
        Quiz $quiz,
        QuizOption $option,
        QuizQuestion $question,
        QuizService $service
    ){
        $this->quiz = $quiz;
        $this->option = $option;
        $this->question = $question;
        $this->quizService = $service;
    }

    public function store(Request $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $quiz = new $this->quiz;

        if(!$request->name){
            $request->name = 'rascunho'.Carbon::now();
        }

        $slug = $this->makeSlug($request->name);

        $quiz->name = $request->name;
        $quiz->slug = $slug;
        $quiz->quiz_description = $request->quiz_description;
        $quiz->status = StatusQuizEnum::EDITING;
        $quiz->initial_date = $request->initial_date;
        $quiz->final_date = $request->final_date;
        $quiz->save();
        return response()->json($quiz, 201);
    }

    public function update(Request $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $quiz = $this->quiz->where('id', $request->id)->update($request->all());
        $quiz = $this->quiz->where('id', $request->id)->firstOrFail();

        return response()->json($quiz, 200);
    }



    public function getOneQuestion($id)
    {
        try{
            $question =  $this->question->where('id', $id)->firstOrFail();
            return response()->json($question, 200);
        }catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function getCompleteQuizBySlug($slug)
    {
        try{
            $quiz = $this->quiz
            ->where('slug', $slug)
            ->with('questions.options')
            ->get();
            return response()->json($quiz);
        } catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function saveOneQuestion(Request $request)
    {
        try{
            $question =  $request->question;
            if(!$question['question']){
                $question['question'] = 'question'.Carbon::now();
            }
            $questionReturn = $this->question->create($question);
            return response()->json($questionReturn, 201);
        }catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function changeQuestionName(Request $request, $id)
    {
        try{
            $question = $this->question->where('id', $request->question['id'])->update($request->question);
            $question = $this->question->where('id',  $request->question['id'])->firstOrFail();
            return response()->json($question, 201);
        }catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function updateQuestion(Request $request)
    {
        try{
            $question = $this->question->where('id', $request->question['id'])->update($request->question);
            $question = $this->question->where('id',  $request->question['id'])->firstOrFail();
            return response()->json($question, 201);
        }catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function removeQuestion($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $question = $this->question->where('id', $id);
        $question->delete();

        return response()->json('Questão removida com sucesso', 200);
    }

    public function saveOneOption(Request $request)
    {
        try{
            $option =  $request->option;
            $optionReturn = $this->option->create($option);
            return response()->json($optionReturn,201);
        }catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function updateOption(Request $request)
    {
        try{
            $option = $this->option->where('id', $request->option['id'])->update($request->option);
            $option = $this->option->where('id',  $request->option['id'])->firstOrFail();

            return response()->json($option, 201);
        }catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function removeOption($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $option = $this->option->where('id', $id);
        $option->delete();

        return response()->json('Opção removida com sucesso', 200);
    }

    public function getQuestionsByQuizId($id)
    {
        try{
            $questions = $this->question->where('quiz_id', $id)->get();
            return response()->json($questions, 200);
        }catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function getOptionsByQuestionId($id)
    {
        try{
            $options = $this->option->where('question_id', $id)->get();
            return response()->json($options, 200);
        }catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function makeSlug($name)
    {

        $slug = Str::slug($name);

        $count = Quiz::where('slug', 'LIKE', '%'. $slug . '%')->count();

        $addCount = $count + 1;

        return $count ? "{$slug}-{$addCount}" : $slug;
    }

    public function getActiveQuiz()
    {
        try{
            $quizzles = $this->quiz
            ->where('status', StatusQuizEnum::ACTIVE)
            ->where('initial_date', '<=', gmdate("Y-m-d\TH:i:s\Z"))
            ->where('final_date', '>=', gmdate("Y-m-d\TH:i:s\Z"))
            ->get();
            return response()->json($quizzles);
        } catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function getAll()
    {
        $quizzes = $this->quiz->get();

        return response()->json($quizzes);
    }

    public function getQuizById($id)
    {
        $quiz = $this->quiz->where('id', $id)->firstOrFail();

        return response()->json($quiz);
    }

    public function publishQuiz($id)
    {
        try{
            $quiz = $this->quiz
                ->where('id', $id)
                ->with('questions.options')
                ->firstOrFail();

            $toReturn = $this->quizService->validateQuestion($quiz);
            return response()->json($toReturn, 200);
        }catch(\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

}
