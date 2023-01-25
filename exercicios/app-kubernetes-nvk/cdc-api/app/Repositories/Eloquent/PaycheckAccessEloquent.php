<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\PaycheckAccessInterface;
use App\Http\Requests\PaycheckAccessStoreRequest;
use App\Http\Requests\PaycheckAccessUpdateRequest;
use App\PaycheckAccess;

class PaycheckAccessEloquent extends AbstractEloquent implements PaycheckAccessInterface {

    public function __construct() {
        parent::__construct('PaycheckAccess');
    }

    public function findOrfail($id)
    {
        return $this->model::with('user')->where('user_id', $id)->firstOrFail();
    }

    public function create(PaycheckAccessStoreRequest $request) {
        return PaycheckAccess::create($request->validated());
    }

    public function update(PaycheckAccessUpdateRequest $request, $model) {
        $model->update($request->validated());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}
