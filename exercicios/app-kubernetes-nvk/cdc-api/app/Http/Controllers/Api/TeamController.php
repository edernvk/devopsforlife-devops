<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TeamInterface;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Resources\Team;
use App\Http\Requests\TeamUpdateRequest;
use App\Http\Resources\TeamCollection;

/**
 * @group Team
 */
class TeamController extends Controller
{
    protected $repository;

    public function __construct(TeamInterface $repository) {
        $this->repository = $repository;
        $this->middleware('auth:api')->except('index');
    }

    /**
     * List Paginated Teams
     *
     * Get a list of paginated teams
     *
     * @responseFile 200 responses/teams.get.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return new TeamCollection($this->repository->paginate());
    }

    /**
     * List of Teams
     *
     * Get a list of all teams
     *
     * @responseFile 200 responses/teams.all.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function all() {
        return $this->repository->all();
    }

    /**
     * Get Teams
     *
     * Get team by it's unique ID.
     *
     * @pathParam id integer required The ID of the user to retrieve. Example: 1
     * @param  \App\Team  $id
     *
     * @authenticated
     * @responseFile 200 responses/teams.show.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $team = $this->repository->findOrfail($id);
        return response()->json(new Team($team));
    }

    /**
     * Store Teams
     *
     * Add a new team to the teams collection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam name string required The name of the user. Example: Dev
     *
     * @authenticated
     * @responseFile 201 responses/teams.store.201.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/404.json
     * @responseFile 422 responses/teams.store.422.json
     * @return \Illuminate\Http\Response
     */
    public function store(TeamStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $team = $this->repository->create($request);

        activity('Team')->causedBy(request()->user())->log('Time salvo: '.$team->name);

        return response()->json(new Team($team), 201);
    }

    /**
     * Update Teams
     *
     * Change information of a user in the teams collection.
     *
     * @pathParam id integer required The ID of the team to retrieve. Example: 1
     * @param  \App\Team  $id
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam name string required The name of the user. Example: Dev Updated
     *
     * @authenticated
     * @responseFile 200 responses/teams.update.200.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/teams.update.404.json
     * @responseFile 422 responses/teams.update.422.json
     * @return \Illuminate\Http\Response
     */
    public function update(TeamUpdateRequest $request, int $id) {
        request()->user()->authorizeRoles(['Administrador']);

        $team = $this->repository->findOrfail($id);
        $team = $this->repository->update($request, $team);

        activity('Team')->causedBy(request()->user())->log('Time alterado: '.$team->name);

        return response()->json(new Team($team));
    }

    /**
     * Delete Teams
     *
     * Delete a team from the teams collection.
     *
     * @pathParam id integer required The ID of the team to retrieve. Example: 1
     * @param  \App\Team  $id
     *
     * @authenticated
     * @response 204 {}
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/users.delete.404.json
     */
    public function destroy($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $team = $this->repository->findOrfail($id);
        $this->repository->delete($team);

        activity('Team')->causedBy(request()->user())->log('Time excluído: '.$team->name);

        return response()->json(null, 204);
    }
}
