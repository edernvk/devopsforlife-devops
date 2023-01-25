<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\StateStoreRequest;
use App\Http\Requests\StateUpdateRequest;

interface StateInterface {
    public function create(StateStoreRequest $request);

    public function update(StateUpdateRequest $request, $state);

    public function delete($state);

    public function getCities($id);
}
