<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RoleInterface;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Role;

class RoleEloquent extends AbstractEloquent implements RoleInterface {

    public function __construct() {
        parent::__construct('Role');
    }

    public function create(RoleStoreRequest $request) {
        return Role::create($request->all());
    }

    public function update(RoleUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}
