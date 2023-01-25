<?php

namespace App\Repositories\Eloquent;

use App\BenefitArea;
use App\Repositories\Interfaces\BenefitDivisionInterface;
use App\Http\Requests\BenefitDivisionStoreRequest;
use App\Http\Requests\BenefitDivisionUpdateRequest;
use App\BenefitDivision;
use Illuminate\Support\Arr;

class BenefitDivisionEloquent extends AbstractEloquent implements BenefitDivisionInterface {

    public function __construct() {
        parent::__construct('BenefitDivision');
    }

    public function all() {
        return $this->model::with('areas')->get();
    }

    public function allWithAreasAndBenefits()
    {
//        $divisionsWithAreasAndBenefitsAndOrphans = $this->model::with([
//            'benefitsWithNoArea' => function ($query) {
//                $query->select(
//                    'id',
//                    'partner',
//                    'contact',
//                    'benefit',
//                    'benefit_division_id',
//                    'benefit_area_id'
//                )->withoutGlobalScopes(['division', 'area']);
//            },
//            'areas.benefits' => function ($query) {
//                $query->select(
//                    'id',
//                    'partner',
//                    'contact',
//                    'benefit',
//                    'benefit_division_id',
//                    'benefit_area_id'
//                )->withoutGlobalScopes(['division', 'area']);
//            },
//            'areas' => function ($query) {
//                $query->withCount('divisions');
//            }
//        ])->get();

        $divisionsWithAreasAndBenefitsAndOrphans = $this->model::with([
            'benefitsWithNoArea' => function ($query) {
                $query->select('id', 'partner', 'contact', 'benefit', 'parentable_id', 'parentable_type');
            },
            'areas.benefits' => function ($query) {
                $query->select('id', 'partner', 'contact', 'benefit', 'parentable_id', 'parentable_type');
            }
        ])->get();

//        // remove repeated benefits from areas in multiple divisions
//        foreach ($divisionsWithAreasAndBenefitsAndOrphans as $division) {
//            foreach($division->areas as $area) {
//                $area->benefits = $area->benefits->filter(function ($benefit, $key) use ($division) {
//                    return $benefit->benefit_division_id == $division->id;
//                });
//            }
//        }

        return $divisionsWithAreasAndBenefitsAndOrphans;
    }

    public function areas($benefitDivision) {
        $division = $this->model::with('areas')->findOrFail($benefitDivision);

        return $division->areas;
    }

    public function create(BenefitDivisionStoreRequest $request) {
        $validated = $request->validated();

        $newDivsion = $this->model::create($validated);

        if (Arr::exists($validated, 'areas')) {
            $areaIds = collect($validated['areas'])->pluck('id');
            $areas = BenefitArea::whereIn('id', $areaIds)->get();
            $newDivsion->areas()->saveMany($areas);
        }

        return $newDivsion;
    }

    public function update(BenefitDivisionUpdateRequest $request, $benefitDivision) {
        $validated = $request->validated();

        $benefitDivision->update($validated);

        if (Arr::exists($validated, 'areas')) {
            $benefitDivision->loadMissing('areas');
            $previousAreas = $benefitDivision->areas;
            $newAreas = $validated['areas'];

            $previousAreasIds = $previousAreas->pluck('id');
            $newAreasIds = collect($newAreas)->pluck('id');

            $removedAreas = collect($previousAreas->toArray())->whereNotIn('id', $newAreasIds);
            $addedAreas = collect($newAreas)->whereNotIn('id', $previousAreasIds);

            if ($removedAreas->count() > 0) {
                $removedAreasIds = $removedAreas->pluck('id');
                $areasToRemove = BenefitArea::whereIn('id', $removedAreasIds)->update([
                    'benefit_division_id' => null
                ]);
            }

            if ($addedAreas->count() > 0) {
                $addedAreasIds = $addedAreas->pluck('id');
                $areasToAdd = BenefitArea::whereIn('id', $addedAreasIds)->update([
                    'benefit_division_id' => $benefitDivision->id
                ]);
            }
        }

        return $benefitDivision;
    }

    public function delete($benefitDivision) {
        $benefitDivision->loadCount(['areas', 'benefitsWithNoArea']);

        abort_if($benefitDivision->areas_count > 0, 409, "Não é possível excluir a divisão pois ela ainda possui áreas.");
        abort_if($benefitDivision->benefits_with_no_area_count > 0, 409, "Não é possível excluir a divisão pois ela ainda possui beneficios.");

        $benefitDivision->delete();
    }
}
