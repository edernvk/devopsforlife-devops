<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ProductInterface;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Product;

class ProductEloquent extends AbstractEloquent implements ProductInterface {

    public function __construct() {
        parent::__construct('Product');
    }

    public function all()
    {
        return Product::all();
    }

    public function create(ProductStoreRequest $request) {
        return Product::create($request->all());
    }

    public function update(ProductUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}
