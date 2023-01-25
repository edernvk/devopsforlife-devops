<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BenefitAreaBulkStoreRequest;
use App\Http\Requests\BenefitAreaStoreRequest;
use App\Http\Requests\BenefitAreaUpdateRequest;
use App\Http\Resources\BenefitAreaCollection;
use App\Http\Resources\BenefitArea as BenefitAreaResource;
use App\Repositories\Interfaces\BenefitAreaInterface;

class BenefitAreaController extends Controller
{
    protected $repository;

    public function __construct(BenefitAreaInterface $repository) {
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
     * @return BenefitAreaCollection
     */
    public function all()
    {
        request()->user()->authorizeRoles(['Administrador']);

        activity('BenefitArea')->causedBy(request()->user())->log('Listagem de áreas dos benefícios (completa)');

        return new BenefitAreaCollection($this->repository->all());
    }

    public function getDivisionless() 
    {
        $areas = $this->repository->getDivisionless();

        return new BenefitAreaCollection($areas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BenefitAreaStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BenefitAreaStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $area = $this->repository->create($request);
        $area->loadMissing('division');

        activity('BenefitArea')->causedBy(request()->user())->log('Área de benefícios salva: '.$area->name);

        return response()->json(new BenefitAreaResource($area), 201);
    }

    public function bulkStore(BenefitAreaBulkStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $areas = $this->repository->bulkCreate($request);

        activity('BenefitArea')->causedBy(request()->user())->log('Multiplas áreas de ramais salvas: '.count($areas));

        return response()->json(BenefitAreaResource::collection($areas), 201);
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

        activity('BenefitArea')->causedBy(request()->user())->log('Área de benefícios consultada: '.$area->name);

        return response()->json(new BenefitAreaResource($area));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BenefitAreaUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BenefitAreaUpdateRequest $request, $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $area = $this->repository->findOrfail($id);
        $area = $this->repository->update($request, $area);
        $area->refresh();
        $area->loadMissing('division');

        activity('BenefitArea')->causedBy(request()->user())->log('Área de benefícios alterada: '.$area->name);

        return response()->json(new BenefitAreaResource($area));
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

        activity('BenefitArea')->causedBy(request()->user())->log('Área de benefícios deletada: '.$area->name);

        return response()->json(null, 204);
    }
}
