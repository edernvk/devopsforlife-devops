<?php

namespace App\Http\Controllers\Api;

use App\Folder;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\FolderInterface;
use App\Http\Requests\FolderStoreRequest;
use App\Http\Requests\FolderUpdateRequest;
use App\Http\Resources\FolderResource;
use App\UsersInFolder;
use Exception;
use Illuminate\Support\Facades\Log;

class FolderController extends Controller
{
    protected $repository;

    public function __construct(FolderInterface $repository) {
        $this->repository = $repository;
    }

    public function index() {
        $folders = $this->repository->all();

        return response()->json($folders);
    }

    public function show($id) {
        $folder = $this->repository->findOrfail($id);

        return response()->json($folder);
    }

    public function store(FolderStoreRequest $request) {
        $folder = $this->repository->create($request);

        return response()->json($folder, 201);
    }

    public function update(FolderUpdateRequest $request, int $id) {
        $model = $this->repository->findOrfail($id);

        $folder = $this->repository->update($request, $model);

        return response()->json($folder, 201);
    }

    public function destroy($id) {
        $folder = $this->repository->findOrfail($id);

        $this->repository->delete($folder);

        return response()->noContent();
    }

    public function getAllWithFiles()
    {
        $folders = $this->repository->allWithFiles();

        return response()->json($folders);
    }

    public function getFolderWithFiles($id)
    {
        $folder = $this->repository->folderWithFiles($id);
        // $model = $this->repository->findOrfail($id);

        return response()->json(new FolderResource($folder));
    }

    public function saveUsersInFolder(Request $request) {
        $users = User::whereIn('id', $request->usersIdArray)->get();
        $folder = $this->repository->findOrfail($request->folderId);

        $folder->usersInFolders()->syncWithoutDetaching($users->pluck('id'));

        return response()->json($folder, 201);
    }

    public function getAllUsersIsNotInFolder($idFolder) {
        $folder = $this->repository->findOrfail($idFolder);
        $folderUsersArray = $folder->usersInFolders()->whereIn('folder_id', [$idFolder])->pluck('user_id');
        return response()->json(User::whereNotIn('id',$folderUsersArray)->get(), 201);
    }

    public function getAllUsersInFolder($idFolder) {
        $folder = $this->repository->findOrfail($idFolder);
        $folderUsersArray = $folder->usersInFolders()->whereIn('folder_id', [$idFolder])->get()->makeHidden('pivot');
        return response()->json($folderUsersArray, 201);
    }

    public function removeUserFromFolder(Request $request) {
        try{
            if(count($request->idUsers) > 0){
                $folder = $this->repository->findOrfail($request->idFolder);
                $folderUsersArray = $folder->usersInFolders()->wherePivot('folder_id', $request->idFolder)->detach($request->idUsers);
                return response()->json($folderUsersArray, 201);
            }
            return response('Lista de usuarios vazia', 403);
        }catch(Exception $e){
            Log::error($e);
            return $e->getMessage();
        }
    }
}
