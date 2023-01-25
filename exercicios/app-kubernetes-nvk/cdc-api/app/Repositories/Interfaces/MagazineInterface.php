<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\MagazineStoreRequest;
use App\Http\Requests\MagazineUpdateRequest;
use App\Http\Requests\UploadCoverRequest;

interface MagazineInterface {
    public function all();

    public function recent();

    public function create(MagazineStoreRequest $request);

    public function update(MagazineUpdateRequest $request, $magazine);

    public function delete($magazine);

    public function storeCover(UploadCoverRequest $request);
}
