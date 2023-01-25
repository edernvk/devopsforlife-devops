<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\ExtensionAreaBulkStoreRequest;
use App\Repositories\Interfaces\ExtensionAreaInterface;
use App\Http\Requests\ExtensionAreaStoreRequest;
use App\Http\Requests\ExtensionAreaUpdateRequest;
use App\ExtensionArea;

class ExtensionAreaEloquent extends AbstractEloquent implements ExtensionAreaInterface {

    public function __construct() {
        parent::__construct('ExtensionArea');
    }

    public function all() {
        return $this->model::with('division')->get();
    }

    public function getDivisionless() {
        return $this->model::where('extension_division_id', null)->get();
    }

    public function create(ExtensionAreaStoreRequest $request) {
        return $this->model::create($request->validated());
    }

    public function bulkCreate(ExtensionAreaBulkStoreRequest $request) {
        $validated = $request->validated();

        // we could multi-insert using `Model::insert` but then it wouldn't return the newly saved entries
        $createdAreas = [];
        foreach ($validated['areas'] as $area) {
            $new = $this->model::create($area);
            $new->loadMissing('division');
            $createdAreas[] = $new;
        }

        return $createdAreas;
    }

    public function update(ExtensionAreaUpdateRequest $request, $extensionArea) {
        $extensionArea->update($request->validated());
        return $extensionArea;
    }

    public function delete($extensionArea) {
        $extensionArea->loadCount('numbers');

        abort_if($extensionArea->numbers_count > 0, 409, 'Não é possível excluir a área pois ela ainda possui ramais.');

        $extensionArea->delete();
    }
}
