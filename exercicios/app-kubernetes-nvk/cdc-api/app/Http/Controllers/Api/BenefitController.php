<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BenefitStoreRequest;
use App\Http\Requests\BenefitUpdateRequest;
use App\Http\Resources\BenefitCollection;
use App\Http\Resources\Benefit as BenefitResource;
use App\Repositories\Interfaces\BenefitInterface;
use App\Http\Controllers\Controller;

class BenefitController extends Controller
{
    protected $repository;

    public function __construct(BenefitInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return BenefitCollection
     */
    public function all()
    {
        activity('Benefit')->causedBy(request()->user())->log('Listagem de benefícios (completa)');

        return new BenefitCollection($this->repository->all());
    }

//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function index()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BenefitStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BenefitStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $benefit = $this->repository->create($request);
        activity('Benefit')->causedBy(request()->user())->log('Benefício salvo: #'.$benefit->id.' - '.$benefit->partner);
        
        return response()->json(new BenefitResource($benefit), 201);
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

        $benefit = $this->repository->findOrfail($id);

        activity('Benefit')->causedBy(request()->user())->log('Benefício consultado: #'.$benefit->id.' - '.$benefit->partner);

        return response()->json(new BenefitResource($benefit));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BenefitUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BenefitUpdateRequest $request, $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $benefit = $this->repository->findOrfail($id);
        $benefit = $this->repository->update($request, $benefit);
        $benefit->refresh();

        activity('Benefit')->causedBy(request()->user())->log('Benefício alterado: #'.$benefit->id.' - '.$benefit->partner);

        return response()->json(new BenefitResource($benefit));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $benefit = $this->repository->findOrfail($id);
        $this->repository->delete($benefit);

        activity('Benefit')->causedBy(request()->user())->log('Benefício deletado: #'.$benefit->id.' - '.$benefit->partner);

        return response()->json(null, 204);
    }
}
