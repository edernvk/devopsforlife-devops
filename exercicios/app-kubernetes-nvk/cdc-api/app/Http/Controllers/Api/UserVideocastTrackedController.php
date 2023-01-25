<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserVideocastTrackedInterface;
use App\Http\Requests\UserVideocastTrackedStoreRequest;
use App\Http\Requests\UserVideocastTrackedUpdateRequest;
use App\Http\Resources\UserVideocastTracked;
use App\Http\Resources\UserVideocastTrackedCollection;

class UserVideocastTrackedController extends Controller
{
    protected $repository;

    public function __construct(UserVideocastTrackedInterface $repository) {
        $this->repository = $repository;
    }

    public function show($id) { }

    public function getPresencesByUser($id) {
        $videosConfirmed = $this->repository->getPresencesByUser($id);

        activity('UserVideocastTracked')->causedBy(request()->user())->log('Listagem de videos confirmados (por usuario)');

        return response()->json(new UserVideocastTrackedCollection($videosConfirmed));
    }

    public function getPresencesByVideo($id) {
        $videosConfirmed = $this->repository->getPresencesByVideo($id);

        activity('UserVideocastTracked')->causedBy(request()->user())->log('Listagem de videos confirmados (por video)');

        return response()->json(new UserVideocastTrackedCollection($videosConfirmed));
    }

    public function store(UserVideocastTrackedStoreRequest $request) {
        $userVideocastTracked = $this->repository->create($request);

        activity('UserVideocastTracked')->causedBy(request()->user())->log('Video confirmado: ' . $userVideocastTracked->participation);

        return response()->json(new UserVideocastTracked($userVideocastTracked), 201);
    }

    public function update(UserVideocastTrackedUpdateRequest $request, int $id) { }

    public function destroy($id) { }
}
