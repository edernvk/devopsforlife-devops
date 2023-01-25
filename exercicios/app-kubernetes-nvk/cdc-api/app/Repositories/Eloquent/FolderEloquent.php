<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\FolderInterface;
use App\Http\Requests\FolderStoreRequest;
use App\Http\Requests\FolderUpdateRequest;
use App\Folder;

class FolderEloquent extends AbstractEloquent implements FolderInterface {

    public function __construct() {
        parent::__construct('Folder');
    }

    public function allWithFiles()
    {
        return Folder::with('files')->get();
    }

    public function folderWithFiles($id)
    {
        return Folder::where('id', $id)->with([
            'files' => function ($query) {
                $query->where('user_id', auth()->id());
            }
        ])->first();
    }

    public function create(FolderStoreRequest $request) {
        return Folder::create($request->all());
    }

    public function update(FolderUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }
}
