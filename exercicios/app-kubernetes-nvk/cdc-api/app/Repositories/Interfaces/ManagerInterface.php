<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\ManagerStoreRequest;
use App\Http\Requests\ManagerUpdateRequest;

interface ManagerInterface extends AbstractInterface {
    public function managersWithCitiesAndStates($model);

    public function removeCitiesManager($manager, $city);

    public function create(ManagerStoreRequest $request);

    public function update(ManagerUpdateRequest $request, $manager);

    public function delete($manager);
}
