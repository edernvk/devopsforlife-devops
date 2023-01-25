<?php

namespace App\Http\Controllers\Api;

use App\ExtensionArea;
use App\Http\Requests\ExtensionNumberStoreRequest;
use App\Http\Requests\ExtensionNumberUpdateRequest;
use App\Http\Resources\ExtensionNumber as ExtensionNumberResource;
use App\Http\Resources\ExtensionNumberCollection;
use App\Repositories\Interfaces\ExtensionNumberInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExtensionNumberController extends Controller
{
    protected $repository;

    public function __construct(ExtensionNumberInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return ExtensionNumberCollection
     */
    public function all()
    {
        activity('ExtensionNumber')->causedBy(request()->user())->log('Listagem de ramais internos (completa)');

        return new ExtensionNumberCollection($this->repository->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExtensionNumberStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExtensionNumberStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $number = $this->repository->create($request);
        // we don't need to lazy load parentable here because it comes from the relationship set already

        activity('ExtensionNumber')->causedBy(request()->user())->log('Ramal salvo: '.$number->name);

        return response()->json(new ExtensionNumberResource($number), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $number = $this->repository->findOrfail($id);

        activity('ExtensionNumber')->causedBy(request()->user())->log('Ramal interno consultado: '.$number->name);

        return response()->json(new ExtensionNumberResource($number));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExtensionNumberUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExtensionNumberUpdateRequest $request, $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $number = $this->repository->findOrfail($id);
        $number = $this->repository->update($request, $number);
        $number->refresh();
        // we don't need to lazy load parentable here because it comes from the relationship set already

        activity('ExtensionNumber')->causedBy(request()->user())->log('Ramal interno alterado: '.$number->name);

        return response()->json(new ExtensionNumberResource($number));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $number = $this->repository->findOrfail($id);
        $this->repository->delete($number);

        activity('ExtensionNumber')->causedBy(request()->user())->log('Ramal interno deletado: '.$number->name);

        return response()->json(null, 204);
    }
}
