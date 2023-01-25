<?php

namespace App\Http\Controllers\Api;

use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ManagerInterface;
use App\Http\Requests\ManagerStoreRequest;
use App\Http\Requests\ManagerUpdateRequest;
use App\Http\Resources\ManagerResource;
use App\Manager;

class ManagerController extends Controller
{
    protected $repository;

    public function __construct(ManagerInterface $repository) {
        $this->repository = $repository;
    }

    public function index() {
        $managers = $this->repository->all();
        return ManagerResource::collection($managers);
    }

    public function getAll()
    {
        $managers = $this->repository->all();
        return ManagerResource::collection($managers);
    }

    public function getManagersWithCitiesAndStates($id)
    {
        $model = $this->repository->findOrfail($id);
        $managers = $this->repository->managersWithCitiesAndStates($model);

        return new ManagerResource($managers);
    }

    public function show($id) {
        $manager = $this->repository->findOrfail($id);
        return new ManagerResource($manager);
    }

    public function store(ManagerStoreRequest $request) {
        $manager = $this->repository->create($request);
        return response()->json(
            new ManagerResource($manager),
            201
        );
    }

    public function update(ManagerUpdateRequest $request, int $id) {
        $model = $this->repository->findOrfail($id);

        $manager = $this->repository->update($request, $model);
        return new ManagerResource($manager);
    }

    public function destroy($id) {
        $model = $this->repository->findOrfail($id);
        $this->repository->delete($model);

        return response()->noContent();
    }

    public function deleteCityFromManager(Manager $manager, City $city)
    {
        $this->repository->removeCitiesManager($manager, $city);

        return response()->noContent();
    }
}
