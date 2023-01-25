<?php

namespace App\Repositories\Eloquent;

use App\Benefit;
use App\BenefitArea;
use App\BenefitDivision;
use App\Repositories\Interfaces\BenefitInterface;
use App\Http\Requests\BenefitStoreRequest;
use App\Http\Requests\BenefitUpdateRequest;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BenefitEloquent extends AbstractEloquent implements BenefitInterface {

    public function __construct() {
        parent::__construct('Benefit');
    }

    public function all() {
        return $this->model::with(['parentable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                BenefitArea::class => ['division'],
            ]);
        }])->get();
    }

    public function findOrfail($benefit) {
        return $this->model::with(['parentable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                BenefitArea::class => ['division'],
            ]);
        }])->findOrfail($benefit);
    }

    public function create(BenefitStoreRequest $request) {
        $validated = $request->validated();

        $benefit = new Benefit();
        $benefit->fill($validated);
        
        $parentable = $this->findParentable($validated);
        $benefit->parentable()->associate($parentable);
        $benefit->save();

        return $benefit;
    }

    public function update(BenefitUpdateRequest $request, $benefit) {
        $validated = $request->validated();

        $benefit->update($validated);

        $parentable = $this->findParentable($validated);

        $benefit->parentable()->associate($parentable);
        $benefit->save();

        return $benefit;

    }

    public function delete($benefit) {
        $benefit->delete();
    }

    private function findParentable($fields) {
        $parentable = null;

        if ($fields['area_id']) {
            $parentable = BenefitArea::findOrFail($fields['area_id']);
        } else {
            $parentable = BenefitDivision::findOrFail($fields['division_id']);
        }

        abort_unless($parentable, 404, 'Nenhuma divisão ou área encontrada.');

        return $parentable;
    }
}
