<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupCollection;
use App\Http\Requests\GroupStoreRequest;
use App\Http\Requests\GroupUpdateRequest;
use Symfony\Component\Console\Input\Input;
use App\Repositories\Interfaces\GroupInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    protected $repository;

    public function __construct(GroupInterface $repository)
    {
        $this->repository = $repository;
        $this->middleware('auth:api');
    }

    public function all()
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return new GroupCollection($this->repository->all());
    }

    public function index()
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return new GroupCollection($this->repository->paginate());
    }

    public function store(GroupStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $group = $this->repository->create($request);
        activity('Group')->causedBy(request()->user())->log('Grupo salvo: ' . $group->name);
        return response()->json(new GroupResource($group), 201);
    }

    public function show($id)
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $group = $this->repository->findOrfail($id);
        return response()->json(new GroupResource($group));
    }

    public function update(GroupUpdateRequest $request, int $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $group = $this->repository->findOrfail($id);
        $group = $this->repository->update($request, $group);
        activity('Group')->causedBy(request()->user())->log('Grupo alterado: ' . $group->name);
        return response()->json(new GroupResource($group));
    }

    public function destroy($id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $group = $this->repository->findOrfail($id);
        $this->repository->delete($group);
        activity('Group')->causedBy(request()->user())->log('Grupo excluído: ' . $group->name);
        return response()->json(null, 204);
    }

    public function showGroups(Group $group)
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $group = $group->load('userGroup');
        return response()->json($group);
    }

    public function addUsers(Group $group, Request $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $request->validate([
            'users' => ['required'],
        ]);

        $users = collect($request->users);
        $users = User::whereIn('id', $users)->get();


        try {
            foreach ($users->chunk(5) as $user) {
                if (!$group->userGroup()->wherePivotIn('user_id', $user)->exists()) {
                    $group->userGroup()->attach($user);
                    return response()->json($users);
                }
                throw new Exception("Usuário já cadastrado");
            }

        } catch (Exception $e) {
            Log::error($e);
            return response()->json($e->getMessage().$e->getCode());
        }
    }

    public function deleteUsers(Group $group, User $user)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $group->userGroup()->detach($user);
        return response()->json('Usuário removido do grupo');
    }

    public function deleteUsersInGroup(Request $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        try {
            if (count($request->idUsers) > 0) {
                $group = $this->repository->findOrfail($request->idGroup);
                $group->userGroup()->wherePivot('group_id', $request->idGroup)->detach($request->idUsers);
                $group = $group->load('userGroup');
                return response()->json($group, 201);
            }
            return response('Lista de usuarios vazia', 403);
        } catch (Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }
}
