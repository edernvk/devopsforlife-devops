<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\BenefitDivisionStoreRequest;
use App\Http\Requests\BenefitDivisionUpdateRequest;

interface BenefitDivisionInterface extends AbstractInterface  {
    public function allWithAreasAndBenefits();

    public function areas($benefitDivision);

    public function create(BenefitDivisionStoreRequest $request);

    public function update(BenefitDivisionUpdateRequest $request, $benefitDivision);

    public function delete($benefitDivision);
}
