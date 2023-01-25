<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\ProductNotFoundStoreRequest;
use App\Http\Requests\ProductNotFoundUpdateRequest;

interface ProductNotFoundInterface {
    public function create(ProductNotFoundStoreRequest $request);

    public function update(ProductNotFoundUpdateRequest $request, $productnotfound);

    public function delete($productnotfound);
}
