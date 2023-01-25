<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\TeamInterface;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Team;

class TeamEloquent extends AbstractEloquent implements TeamInterface {

    public function __construct() {
        parent::__construct('Team');
    }

    public function create(TeamStoreRequest $request) {
        return Team::create($request->all());
    }

    public function update(TeamUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }

    public function getUsers($id) {
        $team = Team::findOrFail($id);
        $team->load('users');

        return $team->users;
    }
}
