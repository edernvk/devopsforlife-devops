<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;

interface TeamInterface {
    public function create(TeamStoreRequest $request);

    public function update(TeamUpdateRequest $request, $team);

    public function delete($team);

    public function getUsers($id);
}
