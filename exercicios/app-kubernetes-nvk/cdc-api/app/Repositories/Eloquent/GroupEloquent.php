<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\GroupInterface;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupUpdateRequest;
use App\Group;

class GroupEloquent extends AbstractEloquent implements GroupInterface {

    public function __construct() {
        parent::__construct('Group');
    }

    public function create(GroupStoreRequest $request) {
        return Group::create($request->all());
    }

    public function update(GroupUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}
