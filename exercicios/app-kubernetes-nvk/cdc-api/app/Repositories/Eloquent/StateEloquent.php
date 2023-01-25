<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\StateInterface;
use App\Http\Requests\StateStoreRequest;
use App\Http\Requests\StateUpdateRequest;
use App\State;

class StateEloquent extends AbstractEloquent implements StateInterface {

    public function __construct() {
        parent::__construct('State');
    }

    public function create(StateStoreRequest $request) {
        return State::create(Request::all());
    }

    public function update(StateUpdateRequest $request, $model) {
        $model->update(Request::all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }

    public function getCities($id) {
        $state = State::findOrFail($id);
        $state->load('cities');

        return $state->cities;
    }
}
