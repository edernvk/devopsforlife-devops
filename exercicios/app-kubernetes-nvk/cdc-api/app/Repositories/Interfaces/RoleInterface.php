<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;

interface RoleInterface {
    public function create(RoleStoreRequest $request);

    public function update(RoleUpdateRequest $request, $role);

    public function delete($role);
}
