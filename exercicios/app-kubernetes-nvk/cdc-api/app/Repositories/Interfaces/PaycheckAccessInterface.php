<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\PaycheckAccessStoreRequest;
use App\Http\Requests\PaycheckAccessUpdateRequest;

interface PaycheckAccessInterface {
    public function create(PaycheckAccessStoreRequest $request);

    public function update(PaycheckAccessUpdateRequest $request, $paycheckaccess);

    public function delete($paycheckaccess);
}
