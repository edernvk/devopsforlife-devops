<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

interface ProductInterface extends AbstractInterface {
    public function create(ProductStoreRequest $request);

    public function update(ProductUpdateRequest $request, $product);

    public function delete($product);
}
