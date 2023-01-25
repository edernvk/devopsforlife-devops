<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\StateInterface;
use App\Http\Requests\StateStoreRequest;
use App\Http\Resources\State;
use App\Http\Requests\StateUpdateRequest;

/**
 * @group State
 */
class StateController extends Controller
{
    protected $repository;

    public function __construct(StateInterface $repository) {
        $this->repository = $repository;
    }
    
    /**
     * List All States
     *
     * Get a list of states
     *
     * @responseFile 200 responses/states.get.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return $this->repository->all();
    }

    /**
     * Get States
     *
     * Get state by it's unique ID.
     *
     * @pathParam id integer required The ID of the state to retrieve. Example: 1
     * @param  \App\State  $id
     *
     * @responseFile 200 responses/states.show.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return $this->repository->findOrfail($id);
    }

    /**
     * Store States
     *
     * Add a state in the states collection.
     *
     * @response 403 {}
     */
    public function store(StateStoreRequest $request) {
        return response()->json(null, 403);
    }

    /**
     * Update States
     *
     * Change insformation of a state in the states collection.
     *
     * @pathParam id integer required The ID of the state to retrieve. Example: 1
     * @param  \App\State  $id
     *
     * @response 403 {}
     */
    public function update(StateUpdateRequest $request, int $id) {
        return response()->json(null, 403);
    }

    /**
     * Delete States
     *
     * Delete a state from states collection.
     *
     * @pathParam id integer required The ID of the state to retrieve. Example: 1
     * @param  \App\State  $id
     *
     * @response 403 {}
     */
    public function destroy($id) {
        return response()->json(null, 403);
    }
}
