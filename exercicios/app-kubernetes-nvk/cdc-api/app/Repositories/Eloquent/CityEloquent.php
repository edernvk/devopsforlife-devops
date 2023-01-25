<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\CityInterface;
use App\Http\Requests\CityStoreRequest;
use App\Http\Requests\CityUpdateRequest;
use App\City;

class CityEloquent extends AbstractEloquent implements CityInterface {

    public function __construct() {
        parent::__construct('City');
    }

    public function create(CityStoreRequest $request) {
        return City::create(Request::all());
    }

    public function update(CityUpdateRequest $request, $model) {
        $model->update(Request::all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}
