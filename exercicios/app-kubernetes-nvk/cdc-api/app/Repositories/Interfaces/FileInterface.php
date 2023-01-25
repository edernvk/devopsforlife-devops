<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\FileStoreRequest;
use App\Http\Requests\FileUpdateRequest;

interface FileInterface extends AbstractInterface {
    public function create(FileStoreRequest $request);

    public function filesByUser($userId);

    public function filesToUser();

    public function update(FileUpdateRequest $request, $file);

    public function delete($file);
}
