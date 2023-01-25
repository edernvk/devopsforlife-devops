<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\HealthDocsStoreRequest;
use App\Http\Requests\HealthDocsUpdateRequest;

interface HealthDocsInterface {
    public function create(HealthDocsStoreRequest $request);

    public function createFromArray(Array $arrayOfData);

    public function update(HealthDocsUpdateRequest $request, $healthdocs);

    public function delete($healthdocs);

    public function storePdf($request);

    public function getFromUser(int $id);
}
