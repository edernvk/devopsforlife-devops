<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\RoleInterface;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Resources\Role;
use App\Http\Requests\RoleUpdateRequest;

/**
 * @group Role
 */
class RoleController extends Controller
{
    protected $repository;

    public function __construct(RoleInterface $repository) {
        $this->repository = $repository;
        $this->middleware('auth:api');
    }

    /**
     * List All Roles
     *
     * Get a list of roles
     *
     * @authenticated
     * @responseFile 200 responses/roles.get.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function index() {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return $this->repository->all();
    }

    /**
     * Get Roles
     *
     * Get role by it's unique ID.
     *
     * @pathParam id integer required The ID of the role to retrieve. Example: 1
     * @param  \App\Role  $id
     *
     * @authenticated
     * @responseFile 200 responses/roles.show.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return $this->repository->findOrfail($id);
    }

    /**
     * Store Roles
     *
     * Add a role in the states collection.
     *
     * @authenticated
     * @response 403 {}
     */
    public function store(RoleStoreRequest $request) {
        return response()->json(null, 403);
    }

    /**
     * Update Roles
     *
     * Change insformation of a role in the roles collection.
     *
     * @pathParam id integer required The ID of the role to retrieve. Example: 1
     * @param  \App\Role  $id
     *
     * @authenticated
     * @response 403 {}
     */
    public function update(RoleUpdateRequest $request, int $id) {
        return response()->json(null, 403);
    }

    /**
     * Delete Roles
     *
     * Delete a role from roles collection.
     *
     * @pathParam id integer required The ID of the role to retrieve. Example: 1
     * @param  \App\Role  $id
     *
     * @authenticated
     * @response 403 {}
     */
    public function destroy($id) {
        return response()->json(null, 403);
    }
}
