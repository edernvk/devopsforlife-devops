<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\FolderStoreRequest;
use App\Http\Requests\FolderUpdateRequest;

interface FolderInterface extends AbstractInterface {
    public function allWithFiles();

    public function folderWithFiles($id);

    public function create(FolderStoreRequest $request);

    public function update(FolderUpdateRequest $request, $folder);

    public function delete($folder);
}
