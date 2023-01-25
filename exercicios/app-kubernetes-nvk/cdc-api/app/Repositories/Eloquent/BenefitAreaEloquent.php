<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\BenefitAreaBulkStoreRequest;
use App\Repositories\Interfaces\BenefitAreaInterface;
use App\Http\Requests\BenefitAreaStoreRequest;
use App\Http\Requests\BenefitAreaUpdateRequest;

class BenefitAreaEloquent extends AbstractEloquent implements BenefitAreaInterface {

    public function __construct() {
        parent::__construct('BenefitArea');
    }

    public function all() {
        return $this->model::with('division')->get();
    }

    public function getDivisionless() {
        return $this->model::where('benefit_division_id', null)->get();
    } 

    public function create(BenefitAreaStoreRequest $request) {
        return $this->model::create($request->validated());
    }

    public function bulkCreate(BenefitAreaBulkStoreRequest $request) {
        $validated = $request->validated();

        $createdAreas = [];
        foreach ($validated['areas'] as $area) {
            $new = $this->model::create($area);
            $new->loadMissing('division');
            $createdAreas[] = $new;
        }
    
        return $createdAreas;
    }

    public function update(BenefitAreaUpdateRequest $request, $benefitArea) {
        $benefitArea->update($request->validated());
        return $benefitArea;
    }

    public function delete($benefitArea) {
        $benefitArea->loadCount('benefits');

        abort_if($benefitArea->benefits_count > 0, 409, "Não é possível excluir a área pois ela ainda possui beneficios.");

        $benefitArea->delete();
    }
}
