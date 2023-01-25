<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\BenefitAreaBulkStoreRequest;
use App\Http\Requests\BenefitAreaStoreRequest;
use App\Http\Requests\BenefitAreaUpdateRequest;

interface BenefitAreaInterface extends AbstractInterface  {
    public function all();

    public function getDivisionless();

    public function create(BenefitAreaStoreRequest $request);

    public function bulkCreate(BenefitAreaBulkStoreRequest $request);

    public function update(BenefitAreaUpdateRequest $request, $benefitArea);

    public function delete($benefitArea);
}
