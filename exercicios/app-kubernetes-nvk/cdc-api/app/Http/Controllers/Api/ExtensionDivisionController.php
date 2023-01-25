<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ExtensionDivisionStoreRequest;
use App\Http\Requests\ExtensionDivisionUpdateRequest;
use App\Http\Resources\ExtensionAreaCollection;
use App\Http\Resources\ExtensionDivision as ExtensionDivisionResource;
use App\Http\Resources\ExtensionDivisionCollection;
use App\Repositories\Interfaces\ExtensionDivisionInterface;
use App\Http\Controllers\Controller;

class ExtensionDivisionController extends Controller
{
    protected $repository;

    public function __construct(ExtensionDivisionInterface $repository) {
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
     * @return ExtensionDivisionCollection
     */
    public function all()
    {
        request()->user()->authorizeRoles(['Administrador']);

        activity('BenefitDivision')->causedBy(request()->user())->log('Listagem de divisões dos benefícios (completa)');

        return new ExtensionDivisionCollection($this->repository->all());
    }

    public function getAreas($id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $areas = $this->repository->areas($id);

        return new ExtensionAreaCollection($areas);
    }

    public function allDivisionsWithAreasAndNumbers()
    {
        // user listing view
        $divisions = $this->repository->allWithAreasAndNumbers();

        return new ExtensionDivisionCollection($divisions);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $division = $this->repository->findOrfail($id);
        $division->loadMissing('areas');

        activity('ExtensionDivision')->causedBy(request()->user())->log('Divisão de ramais consultada: '.$division->name);

        return response()->json(new ExtensionDivisionResource($division));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ExtensionDivisionStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExtensionDivisionStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $division = $this->repository->create($request);
        $division->loadMissing('areas');
        activity('ExtensionDivision')->causedBy(request()->user())->log('Divisão de ramais salva: '.$division->name);

        return response()->json(new ExtensionDivisionResource($division), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ExtensionDivisionUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExtensionDivisionUpdateRequest $request, $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $division = $this->repository->findOrfail($id);
        $division = $this->repository->update($request, $division);
        $division->refresh();
        $division->loadMissing('areas');

        activity('ExtensionDivision')->causedBy(request()->user())->log('Divisão de ramais alterada: '.$division->name);

        return response()->json(new ExtensionDivisionResource($division));
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

        activity('ExtensionDivision')->causedBy(request()->user())->log('Divisão de ramais deletada: '.$division->name);

        return response()->json(null, 204);
    }
}
