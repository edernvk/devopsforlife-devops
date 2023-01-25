<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\CommentInterface;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Comment;

class CommentEloquent extends AbstractEloquent implements CommentInterface {

    public function __construct() {
        parent::__construct('Comment');
    }

    public function create(CommentStoreRequest $request) {
        return Comment::create($request->all());
    }

    public function update(CommentUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}
