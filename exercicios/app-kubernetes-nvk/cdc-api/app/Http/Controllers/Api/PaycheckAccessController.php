<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PaycheckAccess;
use App\Repositories\Interfaces\PaycheckAccessInterface;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\PaycheckAccessStoreRequest;
use App\Http\Requests\PaycheckAccessUpdateRequest;
use App\Http\Controllers\Controller;

class PaycheckAccessController extends Controller
{
    protected $repository;

    public function __construct(PaycheckAccessInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Handle the incoming request.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user) {
        if ($user->id !== auth()->user()->id && !auth()->user()->hasRole('Administrador')) {
            abort(403, 'This action is forbidden.');
        }

        $accessData = $this->repository->findOrFail($user->id);

        activity('PaycheckAccess')->causedBy(request()->user())->log('Acesso do holerite consultado: '.$accessData->cpf);

        return response()->json(new PaycheckAccess($accessData));
    }

    public function store(PaycheckAccessStoreRequest $request, User $user) {
        request()->user()->authorizeRoles(['Administrador']);

        $accessData = $this->repository->create($request);

        activity('PaycheckAccess')->causedBy(request()->user())->log('Acesso do holerite salvo: '.$accessData->cpf);

        return response()->json(new PaycheckAccess($accessData), 201);
    }

    public function update(PaycheckAccessUpdateRequest $request, User $user) {
        request()->user()->authorizeRoles(['Administrador']);

        $accessData = $this->repository->findOrFail($user->id);
        $accessData = $this->repository->update($request, $accessData);

        activity('PaycheckAccess')->causedBy(request()->user())->log('Acesso do holerite alterado: '.$accessData->cpf);

        return response()->json(new PaycheckAccess($accessData));
    }

    public function destroy(User $user) {
        request()->user()->authorizeRoles(['Administrador']);

        $accessData = $this->repository->findOrFail($user->id);
        $this->repository->delete($accessData);

        activity('PaycheckAccess')->causedBy(request()->user())->log('Acesso do holerite deletado: '.$accessData->cpf);

        return response()->json(null, 204);
    }

}
