<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\VideocastStoreRequest;
use App\Http\Requests\VideocastUpdateRequest;

interface VideocastInterface {
    public function create(VideocastStoreRequest $request);

    public function update(VideocastUpdateRequest $request, $videocast);

    public function delete($videocast);

    public function read($model, $user);

    public function countUsersRead($model);

    public function countUsersUnread($model, $reads);

    public function usersReadView($model);

    public function usersUnreadView($model);

    public function percentageUsersRead($model, $reads);

    public function findByUserRead($id, $idUser);
}
