<?php

namespace App\Repositories\Eloquent;

use App\ExtensionArea;
use App\ExtensionDivision;
use App\Repositories\Interfaces\ExtensionNumberInterface;
use App\Http\Requests\ExtensionNumberStoreRequest;
use App\Http\Requests\ExtensionNumberUpdateRequest;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Builder;

class ExtensionNumberEloquent extends AbstractEloquent implements ExtensionNumberInterface {

    public function __construct() {
        parent::__construct('ExtensionNumber');
    }

    public function all() {
        // it needs to load morphWith and the division when querying all,
        // because the number doesnt have a relationship with division
        return $this->model::with(['parentable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                ExtensionArea::class => ['division'],
            ]);
        }])->get();
    }

    public function findOrfail($extensionNumber) {
        return $this->model::with(['parentable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                ExtensionArea::class => ['division'],
            ]);
        }])->findOrFail($extensionNumber);
    }

    public function create(ExtensionNumberStoreRequest $request) {
        $validated = $request->validated();

        $number = new $this->model;
        $number->fill($validated);

        $parentable = $this->findParentable($validated);

        $number->parentable()->associate($parentable);
        $number->save();
//        $number->loadMorph('parentable', [
//            ExtensionArea::class => ['division']
//        ]);
//        $number->loadMissing();

        return $number;
    }

    public function update(ExtensionNumberUpdateRequest $request, $extensionNumber) {
        $validated = $request->validated();

        $extensionNumber->update($validated);

        $parentable = $this->findParentable($validated);

        $extensionNumber->parentable()->associate($parentable);
        $extensionNumber->save();
//        $number->loadMorph('parentable', [
//            ExtensionArea::class => ['division']
//        ]);
//        $number->loadMissing();

        return $extensionNumber;
    }

    public function delete($extensionNumber) {
        $extensionNumber->delete();
    }

    private function findParentable($fields) {
        $parentable = null;

        if ($fields['area_id']) {
            $parentable = ExtensionArea::findOrFail($fields['area_id']);
        } else {
            $parentable = ExtensionDivision::findOrFail($fields['division_id']);
        }

        abort_unless($parentable, 404, 'Nenhuma divisão ou área encontrada.');

        return $parentable;
    }
}
