<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;

interface CommentInterface extends AbstractInterface{

    public function create(CommentStoreRequest $request);

    public function update(CommentUpdateRequest $request, $comment);

    public function delete($comment);
}
