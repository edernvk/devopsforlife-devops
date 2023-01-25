<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExtensionAreaBulkStoreRequest;
use App\Http\Requests\ExtensionAreaStoreRequest;
use App\Http\Requests\ExtensionAreaUpdateRequest;
use App\Http\Resources\ExtensionAreaCollection;
use App\Http\Resources\ExtensionArea as ExtensionAreaResource;
use App\Repositories\Interfaces\ExtensionAreaInterface;

class ExtensionAreaController extends Controller
{
    protected $repository;

    public function __construct(ExtensionAreaInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(405);
        return null;
    }

    /**
     * Display a listing of the resource.
     *
     * @return ExtensionAreaCollection
     */
    public function all()
    {
        request()->user()->authorizeRoles(['Administrador']);

        activity('ExtensionArea')->causedBy(request()->user())->log('Listagem de áreas dos ramais (completa)');

        return new ExtensionAreaCollection($this->repository->all());
    }

    /**
     * Display a listing of the resource.
     */
    public function getDivisionless() 
    {
        request()->user()->authorizeRoles(['Administrador']);

        activity('ExtensionArea')->causedBy(request()->user())->log('Listagem de areas dos ramais, sem divisao');
        
        return new ExtensionAreaCollection($this->repository->getDivisionless());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ExtensionAreaStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExtensionAreaStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $area = $this->repository->create($request);
        $area->loadMissing('division');

        activity('ExtensionArea')->causedBy(request()->user())->log('Área de ramais salva: '.$area->name);

        return response()->json(new ExtensionAreaResource($area), 201);
    }

    /**
     * Store a newly created list of resources in storage.
     *
     * @param  ExtensionAreaBulkStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkStore(ExtensionAreaBulkStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $areas = $this->repository->bulkCreate($request);

        activity('ExtensionArea')->causedBy(request()->user())->log('Multiplas áreas de ramais salvas: '.count($areas));

        return response()->json(ExtensionAreaResource::collection($areas), 201);
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

        $area = $this->repository->findOrfail($id);
        $area->loadMissing('division');

        activity('ExtensionArea')->causedBy(request()->user())->log('Área de ramais consultada: '.$area->name);

        return response()->json(new ExtensionAreaResource($area));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ExtensionAreaUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExtensionAreaUpdateRequest $request, $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $area = $this->repository->findOrfail($id);
        $area = $this->repository->update($request, $area);
        $area->refresh();
        $area->loadMissing('division');

        activity('ExtensionArea')->causedBy(request()->user())->log('Área de ramais alterada: '.$area->name);

        return response()->json(new ExtensionAreaResource($area));
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

        $area = $this->repository->findOrfail($id);
        $this->repository->delete($area);

        activity('ExtensionArea')->causedBy(request()->user())->log('Área de ramais deletada: '.$area->name);

        return response()->json(null, 204);
    }
}
