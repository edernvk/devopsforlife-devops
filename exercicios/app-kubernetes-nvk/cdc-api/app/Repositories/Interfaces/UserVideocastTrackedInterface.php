<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\UserVideocastTrackedStoreRequest;
use App\Http\Requests\UserVideocastTrackedUpdateRequest;

interface UserVideocastTrackedInterface {
    public function getPresencesByUser(int $id);

    public function getPresencesByVideo(int $id);

    public function create(UserVideocastTrackedStoreRequest $request);

    public function update(UserVideocastTrackedUpdateRequest $request, $uservideocasttracked);

    public function delete($uservideocasttracked);
}
