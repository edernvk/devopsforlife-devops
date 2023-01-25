<?php

namespace App\Repositories\Eloquent;

use App\Enums\StatusFileEnum;
use App\File;
use App\Repositories\Interfaces\FileSignatureInterface;
use App\Http\Requests\FileSignatureStoreRequest;
use App\Http\Requests\FileSignatureUpdateRequest;
use App\FileSignature;
use App\Repositories\Interfaces\FileInterface;
use App\User;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class FileSignatureEloquent extends AbstractEloquent implements FileSignatureInterface {

    private FileInterface $fileRepository;
    public function __construct(FileInterface $fileRepository) {
        $this->fileRepository = $fileRepository;
        parent::__construct('FileSignature');
    }

    public function create(FileSignatureStoreRequest $request) {
        $user = auth()->user();
        $fileId = $request->input('file_id');

        $signedAlreadExists = FileSignature::where('user_id', $user->id)
        ->where('file_id', $fileId)
        ->first();

        abort_if($signedAlreadExists, 409, "Esse documento ja foi assinado");
        $file = $this->fileRepository->findOrfail($fileId);
        $fileTeste = $file->users()->whereIn('file_id', [$fileId])->get()->makeHidden('pivot');

        if(count($fileTeste)<2){
            if ($file->status == StatusFileEnum::PENDING) {
                $file->update([
                    'status' => StatusFileEnum::SIGNED
                ]);
            }
        }

        $sing = hash_hmac('sha256', $user->cpf, config('app.key'));

        $signed = new FileSignature;

        $signed->fill([
            'ip' => $request->getClientIp(),
            'user_id' => $user->id,
            'file_id' => $fileId,
            'sing' => $sing
        ]);

        $this->mergeFileSinged($signed, $file);

        $signed->save();

        return $signed;
    }

    private function generateSingedFilePdf(FileSignature $signed, File $fileModel): string
    {
        $file = Pdf::loadView('pdf.file-signed', [
            'file' => $fileModel,
            'sing' => $signed,
            'date' => now()->format('d-m-Y H:i')
        ])->stream('');

        Storage::disk('public')->put("signed-file/$signed->sing.pdf", $file);

        return storage_path("app/public/signed-file/$signed->sing.pdf");
    }

    private function mergeFileSinged(FileSignature $signedFile, File $file)
    {
        $signedFile = $this->generateSingedFilePdf($signedFile, $file);
        $originalFile = Storage::url('/files/'.$file->hashcode.".pdf");
        $output = storage_path("app/public/files/$file->hashcode.pdf");

        $fileExists = $file->hashcode;
        $files = [
            $originalFile,
            $signedFile
        ];

        $fpdi = new Fpdi;
        foreach ($files as $file) {
            $filename  = $file;
            $count =  $this->treatInStorageOrS3($fpdi, $filename);
            for ($i=1; $i<=$count; $i++) {
                $template   = $fpdi->importPage($i);
                $size       = $fpdi->getTemplateSize($template);
                $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
                $fpdi->useTemplate($template);
            }
        }

        $fpdi->Output('F', $output, true);
        $fileToS3 = file_get_contents($output);

        $this->fileRepository->publishToS3($fileToS3, '/files/'.$fileExists.'.pdf');
    }

    public function update(FileSignatureUpdateRequest $request, $model) {
        $model->update($request->all());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }

    public function treatInStorageOrS3(Fpdi $fpdi, string $filename){
        if(str_contains($filename, 's3')){
            return $fpdi->setSourceFile(StreamReader::createByString(file_get_contents($filename)));
        }
        return $fpdi->setSourceFile($filename);
    }
}
