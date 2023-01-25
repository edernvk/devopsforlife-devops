<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\UploadCoverRequest;
use App\Repositories\Interfaces\MagazineInterface;
use App\Http\Requests\MagazineStoreRequest;
use App\Http\Requests\MagazineUpdateRequest;
use App\Magazine;
use Illuminate\Support\Facades\Storage;

class MagazineEloquent extends AbstractEloquent implements MagazineInterface {

    public function __construct() {
        parent::__construct('Magazine');
    }

    public function all() {
        return $this->model::latest()->get();
    }

    public function recent() {
        return $this->model::latest()->take(4)->get();
    }

    // @Override
    // In this case, should return most recent first
    public function paginate() {
        return $this->model::latest()->paginate();
    }

    public function create(MagazineStoreRequest $request) {
        return Magazine::create($request->all());
    }

    public function update(MagazineUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }

    public function storeCover(UploadCoverRequest $request) {
//        if($request->hasFile('cover')) {
//            $filePath = $request->file('cover')->move(
//                storage_path('app/public/magazines-covers'),
//                time().rand(1,100).$request->file('cover')->getClientOriginalName()
//            )->getFilename();
//
//            $path = Storage::url('magazines-covers/'.$filePath);
//
//            return $path;
//        } else {
//            return null;
//        }

        $nameFile = null;
        if ($request->hasFile('cover') && $request->file('cover')->isValid()) {

            $nameFile = time().rand(1,100).$request->file('cover')->getClientOriginalName();
//            $extension = $request->cover->extension();
//            $nameFile = "{$name}.{$extension}";

            $upload = $request->cover->storeAs('magazines-covers', $nameFile);
            $path = Storage::url($upload);

            if ($upload) {
                return [
                    'url' => $path,
                    'filename' => $nameFile
                ];
            }

            return null;

        }
    }
}
