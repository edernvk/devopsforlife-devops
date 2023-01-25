<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CityInterface;
use App\Http\Requests\CityStoreRequest;
use App\Http\Resources\City;
use App\Http\Requests\CityUpdateRequest;
use App\Repositories\Interfaces\StateInterface;
use App\Http\Resources\CityCollection;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

/**
 * @group City
 */
class CityController extends Controller
{
    protected $repository;
    protected $stateRepository;

    public function __construct(
        CityInterface $repository,
        StateInterface $stateRepository
    ) {
        $this->repository = $repository;
        $this->stateRepository = $stateRepository;
    }

    /**
     * Get Cities by State
     *
     * Get cities by states's unique ID.
     *
     * @pathParam id integer required The ID of the state to retrieve the cities. Example: 1
     * @param  \App\State  $id
     *
     * @responseFile 200 responses/cities.bystate.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function allByState($id) {
        $cities = $this->stateRepository->getCities($id);
        return response()->json(new CityCollection($cities));
    }
}
