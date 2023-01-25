<?php

namespace App\Repositories\Eloquent;

use App\ExtensionArea;
use Illuminate\Support\Arr;
use App\Repositories\Interfaces\ExtensionDivisionInterface;
use App\Http\Requests\ExtensionDivisionStoreRequest;
use App\Http\Requests\ExtensionDivisionUpdateRequest;
use App\ExtensionDivision;

class ExtensionDivisionEloquent extends AbstractEloquent implements ExtensionDivisionInterface {

    public function __construct() {
        parent::__construct('ExtensionDivision');
    }

    public function allWithAreasAndNumbers()
    {
        $divisionsWithAreasAndNumbersAndOrphans = $this->model::with([
            'numbersWithNoArea' => function ($query) {
                $query->select('id', 'name', 'number', 'parentable_id', 'parentable_type');
            },
            'areas.numbers' => function ($query) {
                $query->select('id', 'name', 'number', 'parentable_id', 'parentable_type');
            }
        ])->get();

        // remove repeated numbers from areas in multiple divisions
//        foreach ($divisionsWithAreasAndNumbersAndOrphans as $division) {
//            foreach($division->areas as $area) {
//                $area->numbers = $area->numbers->filter(function ($number, $key) use ($division) {
//                    return $number->division_id == $division->id;
//                });
//            }
//        }

        return $divisionsWithAreasAndNumbersAndOrphans;
    }

    public function areas($extensionDivision) {
        $division = $this->model::with('areas')->findOrFail($extensionDivision);

        return $division->areas;
    }

    public function create(ExtensionDivisionStoreRequest $request) {
        $validated = $request->validated();

        $newDivision = $this->model::create($validated);

        if (Arr::exists($validated, 'areas')) {
            $areasIds = collect($validated['areas'])->pluck('id');
            $areas = ExtensionArea::whereIn('id', $areasIds)->get();
            $newDivision->areas()->saveMany($areas);

            // dump($areas->toArray());
        }

        return $newDivision;
    }

    public function update(ExtensionDivisionUpdateRequest $request, $extensionDivision) {
        $validated = $request->validated();

        $extensionDivision->update($validated);

        if (Arr::exists($validated, 'areas')) {

            $extensionDivision->loadMissing('areas');
            $previousAreas = $extensionDivision->areas;
            $newAreas = $validated['areas'];

            // previous areas
            // new areas
            // - removed areas (previous but not new)
            // - added areas (new but not previous)

            $previousAreasIds = $previousAreas->pluck('id');
            $newAreasIds = collect($newAreas)->pluck('id');

            $removedAreas = collect($previousAreas->toArray())->whereNotIn('id', $newAreasIds);
            $addedAreas = collect($newAreas)->whereNotIn('id', $previousAreasIds);

            if ($removedAreas->count() > 0) {
                $removedAreasIds = $removedAreas->pluck('id');
                $areasToRemove = ExtensionArea::whereIn('id', $removedAreasIds)->update([
                    'extension_division_id' => null
                ]);
            }

            if ($addedAreas->count() > 0) {
                $addedAreasIds = $addedAreas->pluck('id');
                $areasToAdd = ExtensionArea::whereIn('id', $addedAreasIds)->update([
                    'extension_division_id' => $extensionDivision->id
                ]);
            }
        }

        return $extensionDivision;
    }

    public function delete($extensionDivision) {

        $extensionDivision->loadCount(['areas', 'numbersWithNoArea']);

        abort_if($extensionDivision->areas_count > 0, 409, 'Não é possível excluir a divisão pois ela ainda possui áreas.');
        abort_if($extensionDivision->numbers_with_no_area_count > 0, 409, 'Não é possível excluir a divisão pois ela ainda possui ramais.');

        $extensionDivision->delete();
    }
}
