<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BenefitDivisionStoreRequest;
use App\Http\Requests\BenefitDivisionUpdateRequest;
use App\Http\Resources\BenefitAreaCollection;
use App\Http\Resources\BenefitDivision as BenefitDivisionResource;
use App\Http\Resources\BenefitDivisionCollection;
use App\Repositories\Interfaces\BenefitDivisionInterface;
use App\Http\Controllers\Controller;

class BenefitDivisionController extends Controller
{
    protected $repository;

    public function __construct(BenefitDivisionInterface $repository) {
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
     * @return BenefitDivisionCollection
     */
    public function all()
    {
        request()->user()->authorizeRoles(['Administrador']);

        activity('BenefitDivision')->causedBy(request()->user())->log('Listagem de divisões dos benefícios (completa)');

        return new BenefitDivisionCollection($this->repository->all());
    }

    public function getAreas($id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $areas = $this->repository->areas($id);

        return new BenefitAreaCollection($areas);
    }

    public function allDivisionsWithAreasAndBenefits()
    {
        // user listing view
        $divisions = $this->repository->allWithAreasAndBenefits();

        return new BenefitDivisionCollection($divisions);
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

        $division = $this->repository->findOrfail($id);
        $division->loadMissing('areas');

        activity('BenefitDivision')->causedBy(request()->user())->log('Divisão de benefícios consultada: '.$division->name);

        return response()->json(new BenefitDivisionResource($division));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BenefitDivisionStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BenefitDivisionStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $division = $this->repository->create($request);
        $division->loadMissing('areas');

        activity('BenefitDivision')->causedBy(request()->user())->log('Divisão de benefícios salva: '.$division->name);

        return response()->json(new BenefitDivisionResource($division), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BenefitDivisionUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BenefitDivisionUpdateRequest $request, $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $division = $this->repository->findOrfail($id);
        $division = $this->repository->update($request, $division);
        $division->refresh();
        $division->loadMissing('areas');

        activity('BenefitDivision')->causedBy(request()->user())->log('Divisão de benefícios alterada: '.$division->name);

        return response()->json(new BenefitDivisionResource($division));
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

        $division = $this->repository->findOrfail($id);
        $this->repository->delete($division);

        activity('BenefitDivision')->causedBy(request()->user())->log('Divisão de benefícios deletada: '.$division->name);

        return response()->json(null, 204);
    }
}
