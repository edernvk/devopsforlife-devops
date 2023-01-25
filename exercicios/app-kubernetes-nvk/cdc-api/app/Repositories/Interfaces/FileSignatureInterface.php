<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\FileSignatureStoreRequest;
use App\Http\Requests\FileSignatureUpdateRequest;

interface FileSignatureInterface extends AbstractInterface {
    public function create(FileSignatureStoreRequest $request);

    public function update(FileSignatureUpdateRequest $request, $filesignature);

    public function delete($filesignature);
}
