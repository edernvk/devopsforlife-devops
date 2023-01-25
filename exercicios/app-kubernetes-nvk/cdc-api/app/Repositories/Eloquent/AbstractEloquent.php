<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AbstractInterface;
use Carbon\Carbon;

class AbstractEloquent implements AbstractInterface {
    protected $model;
 
    public function __construct(string $model) {
        $this->model = "App\\$model";
    }

    public function findOrfail($id) {
        return $this->model::findOrfail($id);
    }

    public function with($id, $string) {
        return $this->model::with($string)->find($id);
        // $result = $this->model::findOrFail($id);
        // $result->load($string);
    }

    public function paginate() {
        return $this->model::paginate();
    }

    public function all() {
        return $this->model::all();
    }

    public function count() {
        return $this->model::count();
    }

    public function createdFrom($days) {
        $date = Carbon::today()->subDays($days);
        return  $this->model::where('created_at', '>=', $date)->get();
    }
}
