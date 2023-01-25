<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\ExtensionDivisionStoreRequest;
use App\Http\Requests\ExtensionDivisionUpdateRequest;

interface ExtensionDivisionInterface extends AbstractInterface {
    public function allWithAreasAndNumbers();

    public function areas($extensionDivision);

    public function create(ExtensionDivisionStoreRequest $request);

    public function update(ExtensionDivisionUpdateRequest $request, $extensionDivision);

    public function delete($extensionDivision);
}
