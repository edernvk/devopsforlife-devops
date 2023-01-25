<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait UploadTrait
{

    /**
     * @param UploadedFile $uploadedFile    Uploaded file from request
     * @param string|null $folder           Destiny folder name on disk (defaults to public root)
     * @param string $disk                  Destiny disk name (defaults to `public`)
     * @param string|null $filename         File name to be stored (if null, FileHelper::hashName() will be used)
     *
     * @return false|string                 Returns file path (`folder-name/filename.ext`)
     */
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 's3', $filename = null)
    {
        // name + extension
        $basename = !is_null($filename) ? $filename.'.'.$uploadedFile->getClientOriginalExtension() : $uploadedFile->hashName();

        return $uploadedFile->storeAs($folder, $basename, $disk);
    }
}
