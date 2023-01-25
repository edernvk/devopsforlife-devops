<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\BenefitStoreRequest;
use App\Http\Requests\BenefitUpdateRequest;

interface BenefitInterface extends AbstractInterface {
    public function create(BenefitStoreRequest $request);

    public function update(BenefitUpdateRequest $request, $benefit);

    public function delete($benefit);
}
