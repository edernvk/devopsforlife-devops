<?php

namespace App\Repositories\Eloquent;

use App\Enums\StatusFileEnum;
use App\Repositories\Interfaces\FileInterface;
use App\Http\Requests\FileStoreRequest;
use App\Http\Requests\FileUpdateRequest;
use App\File;
use App\Folder;
use App\User;
use App\FileSignature;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class FileEloquent extends AbstractEloquent implements FileInterface {

    public function __construct() {
        parent::__construct('File');
    }

    public function all() {
        return File::latest()->with('folders')->get();
    }

    public function paginate() {
        return File::latest()->with('folders')->paginate();
    }

    public function create(FileStoreRequest $request) {
        $validated = $request->validated();

        $status = StatusFileEnum::NORMAL;

        if ($validated['accepted']) {
            $status = StatusFileEnum::PENDING;
        }
        if(Storage::disk('s3')->exists($validated['path'])){
            $teste = Storage::disk('s3');
        }
        // Storage::disk('s3')->url($validated['path']);
        $storageUrl = Storage::url('');
        $filesize = Storage::size($validated['path']);


        $hash = hash_hmac('SHA256', implode( '-', [
            now(),
            $filesize,
            $request->getClientIp(),
            $request->user()->cpf
        ]),config('app.key'));

        $hashedFile = $this->addHashInPath($storageUrl .$validated['path'], $hash);

        $newFile = File::create([
            'name' => $validated['name'],
            'user_id' => $validated['user_id'],
            'description' => $validated['description'],
            'path' => $hashedFile,
            'status' => $status,
            'accepted' => $validated['accepted'],
            'deadline' => $validated['deadline'] ?? null,
            'hashcode' => $hash
        ]);

        $to = collect($request->to);
        if($to->isNotEmpty()) {
            for($referencedTo = 0; $referencedTo < $to->count(); $referencedTo++){
                $users = User::whereIn('id', $to[$referencedTo])->get();
                $newFile->users()->attach($users->pluck('id'));
            }
        }

        if (!is_null($validated['folders'])) {
            $folders = Folder::whereIn('id', $validated['folders'])->get();
            $newFile->folders()->attach($folders->pluck('id'));
        }

        // if (Arr::exists($validated, 'to')) {
        //     $newFile->users()->sync($validated['to']);
        // }

        // if (Arr::exists($validated, 'folders')) {
        //     $newFile->folders()->sync($validated['folders']);
        // }

        return $newFile;
    }

    public function filesByUser($userId)
    {
        return File::where('user_id', $userId)
            ->doesntHave('folders')
            ->latest()
            ->get();
    }

    public function filesToUser()
    {
        $files = auth()->user()->files()->latest()->get();

        return $files;
    }

    public function update(FileUpdateRequest $request, $model) {
        $model->update($request->all());

        $validated = $request->validated();

        $path = $this->upload($validated['path']);

        $newFile = $model->update([
            'name' => $validated['name'],
            'user_id' => $validated['user_id'],
            'description' => $validated['description'],
            'path' => $path,
            'accepted' => $validated['accepted'],
            'deadline' => $validated['deadline'],
        ]);

        return $newFile;
    }

    public function delete($model) {
        $model->delete();
    }

    public function upload(UploadedFile $file)
    {
        return Storage::disk('public')->put('',$file);
    }

    public function publishToS3(string $file, $pathInfo = null)
    {
        return Storage::disk('s3')->put($pathInfo, $file);
    }

    private function addHashInPath(string $pathFile, string $hash)
    {
        $output = storage_path("app/public/files/$hash.pdf");

        $fpdi = new Fpdi();
        // merger operations
        // $count = $fpdi->setSourceFile($pathFile);
        $count = $fpdi->setSourceFile(StreamReader::createByString(file_get_contents($pathFile)));
        for ($i=1; $i <= $count; $i++) {
            $template   = $fpdi->importPage($i);
            $size       = $fpdi->getTemplateSize($template);
            $fpdi->AddPage(
                $size['orientation'],
                array($size['width'], $size['height'])
            );
            $fpdi->useTemplate($template);
            $text = 'Hash: ' . $hash;
            $left = (($fpdi->GetPageWidth()/2 - $fpdi->GetPageWidth()/4) -5);
            $top =  $fpdi->GetPageHeight() - 5;
            $fpdi->SetFont("Arial", "", 10);
            $fpdi->SetTextColor(128,128,128);
            $fpdi->Text($left,$top,$text);
        }

        $fpdi->Output('F', $output, true);
        $fileToS3 = file_get_contents($output);
        $bSendedToS3 = $this->publishToS3($fileToS3, '/files/'.$hash.'.pdf');
        if($bSendedToS3){
            return Storage::url('/files/'.$hash.'.pdf');
        }
        // Storage::disk('s3')->put($user->contractDir() . $fileName, $file, 'public');
        return Storage::url("files/$hash.pdf");
    }
}
