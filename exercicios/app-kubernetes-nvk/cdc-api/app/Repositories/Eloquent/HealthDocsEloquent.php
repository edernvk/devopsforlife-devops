<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\HealthDocsInterface;
use App\Http\Requests\HealthDocsStoreRequest;
use App\Http\Requests\HealthDocsUpdateRequest;
use App\HealthDocs;
use Illuminate\Support\Facades\Storage;

class HealthDocsEloquent extends AbstractEloquent implements HealthDocsInterface {

    public function __construct() {
        parent::__construct('HealthDocs');
    }

    public function create(HealthDocsStoreRequest $request) {
        return HealthDocs::create($request->all());
    }

    public function createFromArray(Array $arrayOfData) {
        return HealthDocs::create($arrayOfData);
    }

    public function update(HealthDocsUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }

    public function storePdf($request) {
        if($request->hasFile('pdf')) {
            $filePath = $request->file('pdf')->move(
                storage_path('app/public/users-healthdocs'),
                time().rand(1,100).$request->file('pdf')->getClientOriginalName()
            )->getFilename();

            $path = Storage::url('users-healthdocs/'.$filePath);

            return $path;
        } else {
            return null;
        }
    }

    public function getFromUser(int $id) {
        $docs = HealthDocs::where('user_id', $id)->orderBy('created_at')->get();

        return $docs;
    }
}
