<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\NewsletterInterface;
use App\Http\Requests\NewsletterStoreRequest;
use App\Http\Requests\NewsletterUpdateRequest;
use App\Newsletter;

class NewsletterEloquent extends AbstractEloquent implements NewsletterInterface {

    public function __construct() {
        parent::__construct('Newsletter');
    }

    public function all() {
        return $this->model::latest()->get();
    }

    public function recent() {
        return $this->model::latest()->take(4)->get();
    }

    // @Override
    // In this case, should return most recent first
    public function paginate() {
        return $this->model::latest()->paginate();
    }

    public function create(NewsletterStoreRequest $request) {
        return Newsletter::create($request->validated());
    }

    public function update(NewsletterUpdateRequest $request, $model) {
        $model->update($request->validated());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}
