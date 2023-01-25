<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductInterface;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\Product;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    protected $repository;

    public function __construct(ProductInterface $repository) {
        $this->repository = $repository;
    }

    public function getAll()
    {
        $products = $this->repository->all();
        return ProductResource::collection($products);
    }

    public function index() {
        $products = $this->repository->paginate();
        return ProductResource::collection($products);
    }

    public function show($id) {
        $product = $this->repository->findOrfail($id);

        return new ProductResource($product);
    }

    public function store(ProductStoreRequest $request) {
        $product = $this->repository->create($request);

        return response()->json(new ProductResource($product), 201);
    }

    public function update(ProductUpdateRequest $request, int $id) {
        $model = $this->repository->findOrfail($id);

        $product = $this->repository->update($request, $model);

        return new ProductResource($product);
    }

    public function destroy($id) {
        $model = $this->repository->findOrfail($id);

        $this->repository->delete($model);

        return response()->json(null, 204);
    }
}
