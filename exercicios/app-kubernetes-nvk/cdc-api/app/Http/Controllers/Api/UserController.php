<?php

namespace App\Http\Controllers\Api;

use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserInterface;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\User;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Repositories\Interfaces\TeamInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @group User
 */
class UserController extends Controller
{
    use UploadTrait;

    protected $repository;
    protected $teamRepository;

    public function __construct(
        UserInterface $repository,
        TeamInterface $teamRepository
    ) {
        $this->repository = $repository;
        $this->teamRepository = $teamRepository;
        $this->middleware('auth:api');
    }

    /**
     * List Paginated Users
     *
     * Get a list of paginated users
     *
     * @authenticated
     * @responseFile 200 responses/users.get.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return UserCollection
     */
    public function index() {
        request()->user()->authorizeRoles(['Administrador']);

        activity('User')->causedBy(request()->user())->log('Listagem de usuários (paginada)');

        return new UserCollection($this->repository->paginate());
    }

    /**
     * List of Users
     *
     * Get a list of all users
     *
     * @responseFile 200 responses/users.all.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function all() {
        // activity('User')->causedBy(request()->user())->log('Listagem de usuários (completa)');

        return $this->repository->all();
    }


    public function allCollection() {
        // activity('User')->causedBy(request()->user())->log('Listagem de usuários (completa coleção)');

        return new UserCollection($this->repository->all());
    }

    /**
     * Get Users
     *
     * Get user by it's unique ID.
     *
     * @pathParam id integer required The ID of the user to retrieve. Example: 1
     * @param  \App\User  $id
     *
     * @authenticated
     * @responseFile 200 responses/users.show.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $user = $this->repository->with($id, ['city', 'team']);
        // Log::info($user);

        activity('User')->causedBy(request()->user())->log('Usuário consultado: '.$user->name);

        return response()->json(new User($user));
    }

    /**
     * Store Users
     *
     * Add a new user to the users collection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: johndoe@casadiconti.com.br
     * @bodyParam registration string The company registration number of the user. Example: 99999
     * @bodyParam mobile string required The mobile of the user. Example: (99) 99999-9999
     * @bodyParam avatar string The avatar picture of the user. Example: /storage/user/asdjauisdhasud.jpg
     * @bodyParam city_id integer The city id number. Example: 4630
     * @bodyParam team_id integer The team id number. Example: 1
     * @bodyParam password string required The user access password. Example: senha@1234
     * @bodyParam password_confirmation required The user access password confirmation. Example: senha@1234
     *
     * @authenticated
     * @responseFile 201 responses/users.store.201.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/404.json
     * @responseFile 422 responses/users.store.422.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $user = $this->repository->create($request);

        activity('User')->causedBy(request()->user())->log('Usuário salvo: '.$user->name);

        return response()->json(new User($user), 201);
    }

    /**
     * Update Users
     *
     * Change information of a user in the users collection.
     *
     * @pathParam id integer required The ID of the user to retrieve. Example: 1
     * @param  \App\User  $id
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam name string required The name of the user. Example: John Doe Updated
     * @bodyParam email string required The email of the user. Example: johndoe@casadiconti.com.br
     * @bodyParam registration string The company registration number of the user. Example: 999991
     * @bodyParam mobile string required The mobile of the user. Example: (99) 99999-9991
     * @bodyParam avatar string The avatar picture of the user. Example: /storage/user/asdjauisdhasud_updated.jpg
     * @bodyParam city_id integer The city id number. Example: 4631
     * @bodyParam team_id integer The team id number. Example: 2
     *
     * @authenticated
     * @responseFile 200 responses/users.update.200.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/users.update.404.json
     * @responseFile 422 responses/users.update.422.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, int $id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $user = $this->repository->findOrfail($id);
        $user = $this->repository->update($request, $user);

        activity('User')->causedBy(request()->user())->log('Usuário alterado: '.$user->name);

        return response()->json(new User($user));
    }

    /**
     * Delete Users
     *
     * Delete a user from the users collection.
     *
     * @pathParam id integer required The ID of the user to retrieve. Example: 1
     * @param  \App\User  $id
     *
     * @authenticated
     * @response 204 {}
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/users.delete.404.json
     */
    public function destroy($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $user = $this->repository->findOrfail($id);
        $this->repository->delete($user);

        activity('User')->causedBy(request()->user())->log('Usuário deletado: '.$user->name);

        return response()->json(null, 204);
    }

    /**
     * Update User Password
     *
     * Change password of a user in the users collection.
     *
     * @pathParam id integer required The ID of the user to retrieve. Example: 1
     * @param  \App\User  $id
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam password string required The user access password. Example: senha@1234
     * @bodyParam password_confirmation required The user access password confirmation. Example: senha@1234
     *
     * @authenticated
     * @responseFile 200 responses/users.update-password.200.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/users.update-password.404.json
     * @responseFile 422 responses/users.update-password.422.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UserPasswordUpdateRequest $request, int $id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $user = $this->repository->findOrfail($id);
        $user = $this->repository->updatePassword($user, $request->password);

        activity('User')->causedBy(request()->user())->log('Alterado a senha do usuário: '.$user->name);

        return response()->json(new User($user));
    }

    /**
     * Approve
     *
     * Approve users with a timestamp
     *
     * @pathParam id integer required The ID of the user to retrieve. Example: 1
     * @param  \App\User  $id
     *
     * @authenticated
     * @responseFile 200 responses/users.approve.200.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/users.approve.404.json
     * @responseFile 409 responses/users.approve.409.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(int $id) {
        request()->user()->authorizeRoles(['Administrador']);

        $user = $this->repository->findOrfail($id);

        if($user->approved !== null)
            return response()->json(['message' => 'Usuário já está aprovado'], 409);

        $user = $this->repository->approve($user);

        activity('User')->causedBy(request()->user())->log('Usuário aprovado: '.$user->name);

        return response()->json(new User($user));
    }

    /**
     * Disapprove
     *
     * Disapprove users with a timestamp
     *
     * @pathParam id integer required The ID of the user to retrieve. Example: 1
     * @param  \App\User  $id
     *
     * @authenticated
     * @responseFile 200 responses/users.disapprove.200.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/users.disapprove.404.json
     * @responseFile 409 responses/users.disapprove.409.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function disapprove(int $id) {
        request()->user()->authorizeRoles(['Administrador']);

        $user = $this->repository->findOrfail($id);

        if($user->approved === null)
            return response()->json(['message' => 'Usuário já está desaprovado'], 409);

        $user = $this->repository->disapprove($user);

        activity('User')->causedBy(request()->user())->log('Retirada a aprovação do usuário: '.$user->name);

        return response()->json(new User($user));
    }

    /**
     * Store User's Avatar
     *
     * This endpoint store user's avatar to the public/users-avatars folder in server.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @bodyParam image File Image uploaded.
     *
     * @pathParam id integer User id. Example: 1
     *
     * @authenticated
     * @response {
     *  "location": "users-avatars/123123123-avatars11s.jpg"
     * }
     * @response 400 {}
     */
    public function avatar(int $id, Request $request) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $request->replace($request->all());

        $user = $this->repository->findOrfail($id);

//        $avatar = $this->repository->storeAvatar($user, $request);

        // TODO: should this be split? like magazine cover? upload and only when submit update entry?
        $avatar = $this->uploadOne($request->file('avatar'), 'users-avatars', 's3', null);
        $user->avatar = Storage::url($avatar);
        $user->save();

        if($avatar) {
            return response()->json([
                'url' => Storage::url($avatar),
                'path' => $avatar
            ]);
        }

        return response()->json(null, 400);
    }

    /**
     * Get Users by Team
     *
     * Get users by team's unique ID.
     *
     * @pathParam id integer required The ID of the team to retrieve the users. Example: 1
     * @param  \App\User  $id
     *
     * @responseFile 200 responses/users.byteam.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\Response
     */
    public function allByTeams($id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $users = $this->teamRepository->getUsers($id);
        return response()->json(new UserCollection($users));
    }

    /**
     * Check Terms
     *
     * Check the terms and policies of the company
     *
     * @pathParam id integer required The ID of the user to retrieve. Example: 1
     * @param  \App\User  $id
     *
     * @authenticated
     * @responseFile 200 responses/users.approve.200.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/users.approve.404.json
     * @responseFile 409 responses/users.approve.409.json
     * @return \Illuminate\Http\Response
     */
    public function checkTerms(int $id) {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $user = $this->repository->findOrfail($id);

        if($user->allow_terms !== null)
            return response()->json(['message' => 'Usuário já concordou com os termos'], 409);

        $user = $this->repository->checkTerms($user);

        activity('User')->causedBy(request()->user())->log('Usuário concordou com os termos: '.$user->name);

        return response()->json(new User($user));
    }

    public function getUsersAlert() {
        request()->user()->authorizeRoles(['Administrador']);

        $users = $this->repository->getAlert();

        return response()->json(new UserCollection($users));
    }

    public function birthday() {
        $birthday = $this->repository->nearBirthday();
        return response()->json($birthday);
    }

    public function updateVcard($id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $users = $this->repository->findOrfail($id);

        //return ($users);

        $users->update([
            'vcard_enable' => !$users->vcard_enable
        ]);
        return response()->json(new User($users));
    }

}

