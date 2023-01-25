<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\StatusMessageInterface;
use App\Http\Requests\StatusMessageStoreRequest;
use App\Http\Requests\StatusMessageUpdateRequest;
use App\StatusMessage;

class StatusMessageEloquent extends AbstractEloquent implements StatusMessageInterface {

    public function __construct() {
        parent::__construct('StatusMessage');
    }

    public function create(StatusMessageStoreRequest $request) {
        return StatusMessage::create($request->all());
    }

    public function update(StatusMessageUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}
