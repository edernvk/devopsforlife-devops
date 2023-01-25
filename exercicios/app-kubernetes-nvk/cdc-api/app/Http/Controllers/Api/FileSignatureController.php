<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\FileSignatureInterface;
use App\Http\Requests\FileSignatureStoreRequest;
use App\Http\Resources\FileSignature;
use App\Http\Requests\FileSignatureUpdateRequest;
use App\Http\Resources\FileSignatureResource;
use Illuminate\Support\Facades\Storage;

class FileSignatureController extends Controller
{
    protected $repository;

    public function __construct(FileSignatureInterface $repository) {
        $this->repository = $repository;
    }

    public function index() {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        return FileSignatureResource::collection($this->repository->paginate());
    }

    public function show($id) {
        abort(405);
    }

    public function store(FileSignatureStoreRequest $request) {
        $signature = $this->repository->create($request);

        return $signature;
    }

    public function update(FileSignatureUpdateRequest $request, int $id) {
        abort(405);
    }

    public function destroy($id) {
        abort(405);
    }
}
