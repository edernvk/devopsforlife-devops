<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupUpdateRequest;

interface GroupInterface extends AbstractInterface{
    public function create(GroupStoreRequest $request);

    public function update(GroupUpdateRequest $request, $group);

    public function delete($group);
}
