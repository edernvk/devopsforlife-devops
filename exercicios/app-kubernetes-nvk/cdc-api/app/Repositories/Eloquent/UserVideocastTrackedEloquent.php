<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\UserVideocastTrackedInterface;
use App\Http\Requests\UserVideocastTrackedStoreRequest;
use App\Http\Requests\UserVideocastTrackedUpdateRequest;
use App\UserVideocastTracked;

class UserVideocastTrackedEloquent extends AbstractEloquent implements UserVideocastTrackedInterface {

    public function __construct() {
        parent::__construct('UserVideocastTracked');
    }

    public function getPresencesByUser(int $id) {
        return $this->model::where('user_id', $id)->get();
    }

    public function getPresencesByVideo(int $id) {
        return $this->model::where('videocast_id', $id)->get();
    }

    public function create(UserVideocastTrackedStoreRequest $request) {
        return $this->model::create([
            'user_id' => auth()->user()->id,
            'videocast_id' => $request->input('videocast_id'),
            'participation' => now()->format('Y-m-d H:i:s')
        ]);
    }

    public function update(UserVideocastTrackedUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}
