<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductNotFoundInterface;
use App\Http\Requests\ProductNotFoundStoreRequest;
use App\Http\Resources\ProductNotFound;
use App\Http\Requests\ProductNotFoundUpdateRequest;
use Exception;

class ProductNotFoundController extends Controller
{
    protected $repository;

    public function __construct(ProductNotFoundInterface $repository) {
        $this->repository = $repository;
    }

    public function index() {
        abort(409);
    }

    public function show($id) {
        abort(409);
    }

    public function store(ProductNotFoundStoreRequest $request) {
        try {
            $result =  $this->repository->create($request);
            return response()->json($result);
        } catch (Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    public function update(ProductNotFoundUpdateRequest $request, int $id) {
        abort(409);
    }

    public function destroy($id) {
        abort(409);
    }
}
