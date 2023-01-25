<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\CityStoreRequest;
use App\Http\Requests\CityUpdateRequest;

interface CityInterface {
    public function create(CityStoreRequest $request);

    public function update(CityUpdateRequest $request, $city);

    public function delete($city);
}
