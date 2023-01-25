<?php

namespace App\Http\Controllers\Api;

use App\Enums\StatusFileEnum;
use App\File;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\FileInterface;
use App\Http\Requests\FileStoreRequest;
use App\Http\Requests\FileUpdateRequest;
use App\Http\Resources\FileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FileController extends Controller
{
    protected $repository;

    public function __construct(FileInterface $repository) {
        $this->repository = $repository;
    }

    public function index() {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);

        $files = $this->repository->paginate();

        return FileResource::collection($files->load(['users', 'user', 'signature']));
    }

    public function all()
    {
        request()->user()->authorizeRoles(['Administrador', 'Colaborador']);
        return FileResource::collection($this->repository->all());
    }

    public function show($id) {
        $file = $this->repository->findOrfail($id);
        return response()->json(
            new FileResource($file->load(['signature']))
        );
    }

    public function store(FileStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $file = $this->repository->create($request);

        return response()->json(new FileResource($file), 201);
    }

    public function update(FileUpdateRequest $request, int $id) {
        request()->user()->authorizeRoles(['Administrador']);

        $model = $this->repository->findOrfail($id);

        $file = $this->repository->update($request, $model);

        return response()->json(
            new FileResource($file)
        );
    }

    public function destroy($id) {
        abort(405);
    }

    public function getFilesByUser($id)
    {
        $files = $this->repository->filesByUser($id);

        return FileResource::collection($files->load(['users', 'user', 'signature']));
    }

    public function getFilesToUser()
    {
        $files = $this->repository->filesToUser();

        return FileResource::collection($files->load(['users', 'user', 'signature']));
    }

    public function patchCheckFileExpired($id)
    {
        $file = $this->repository->findOrfail($id);

        $now = now();
        if (
            $file->status === StatusFileEnum::PENDING
            && $now >= $file->deadline
        ) {
            $file->update([
                'status' => StatusFileEnum::EXPIRED
            ]);
            return response()->json(['message' => 'Status do Documento Atualizado']);
        }

        return response()->json(['message' =>  'Status do Documento não foi atualizado']);
    }

    public function viewDocument(int $id)
    {
        $file = $this->repository->findOrfail($id);

        return response()->file(Storage::path($file->path));
    }

    public function postUpload(Request $request) {
        request()->user()->authorizeRoles(['Administrador']);
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10000'
        ], [
            'file.mimes' => 'O arquivo deve estar na extensão .pdf',
            'file.max' => 'O arquivo deve ter até 10MB',
        ]);

        #certificar de ter o ghostscript instalado na maquina, tanto windows quanto linux
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $instructionToExecute =  $isWindows ? "C:\Program Files\gs\gs9.56.1\bin\gswin64.exe" : "gs";


        $isS3upload = true;
        $file = $this->repository->upload($request->file('file'));
        $fileUrl = Storage::disk('public')->path($file);
        $newFileUrl = str_replace('.pdf', 'converted.pdf', $fileUrl);
        $newFileName = str_replace('.pdf', 'converted.pdf', $file);
        $command= `"$instructionToExecute" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$newFileUrl $fileUrl`;
        Storage::disk('public')->delete($file);

        $fileToS3 = file_get_contents($newFileUrl);
        $bSendedToS3 = $this->repository->publishToS3($fileToS3, '/files/'.$newFileName);
        // Storage::disk('s3')->put('/files/'.$newFileName, $fileToS3);
        if($bSendedToS3){
            $newFileUrl = Storage::url('/files/'.$newFileName);
            Storage::disk('public')->delete($newFileName);

            return response()->json([
                'url' => $newFileUrl,
                'path' => 'files/'.$newFileName
            ]);
        }

        return response()->json(['message' =>  'Documento não enviado']);
    }

}
