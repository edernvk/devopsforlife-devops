<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\ExtensionNumberStoreRequest;
use App\Http\Requests\ExtensionNumberUpdateRequest;

interface ExtensionNumberInterface extends AbstractInterface {
    public function all();

    public function findOrfail($extensionNumber);

    public function create(ExtensionNumberStoreRequest $request);

    public function update(ExtensionNumberUpdateRequest $request, $extensionNumber);

    public function delete($extensionNumber);
}
