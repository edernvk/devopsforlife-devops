<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\ExtensionAreaBulkStoreRequest;
use App\Http\Requests\ExtensionAreaStoreRequest;
use App\Http\Requests\ExtensionAreaUpdateRequest;

interface ExtensionAreaInterface extends AbstractInterface {
    public function all();

    public function getDivisionless();

    public function create(ExtensionAreaStoreRequest $request);

    public function bulkCreate(ExtensionAreaBulkStoreRequest $request);

    public function update(ExtensionAreaUpdateRequest $request, $extensionArea);

    public function delete($extensionArea);
}
