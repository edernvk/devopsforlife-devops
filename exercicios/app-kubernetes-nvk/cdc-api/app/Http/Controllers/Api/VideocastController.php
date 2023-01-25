<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\VideocastCollection;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\VideocastInterface;
use App\Http\Requests\VideocastStoreRequest;
use App\Http\Resources\Videocast;
use App\Http\Requests\VideocastUpdateRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserSimplified;

class VideocastController extends Controller
{

    protected $repository;

    public function __construct(VideocastInterface $repository) {
        $this->repository = $repository;
    }

    /**
     * List Paginated videocasts
     *
     * Get a list of paginated videocasts
     *
     * @authenticated
     * @responseFile 200 responses/videocasts.get.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \App\Http\Resources\VideocastCollection
     */
    public function index() {
        activity('Videocast')->causedBy(request()->user())->log('Listagem de videocasts (paginada)');

        return new VideocastCollection($this->repository->paginate());
    }

    /**
     * List of VideoCasts
     *
     * Get a list of all videocasts
     *
     * @responseFile 200 responses/videocasts.all.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \App\Http\Resources\VideocastCollection
     */
    public function all() {
        activity('Videocast')->causedBy(request()->user())->log('Listagem de videocasts (completa)');

        return new VideocastCollection($this->repository->all());
    }

    /**
     * Get Videocasts
     *
     * Get videocast by it's unique ID.
     *
     * @pathParam id integer required The ID of the videocast to retrieve. Example: 1
     * @param  \App\Videocast  $id
     *
     * @authenticated
     * @responseFile 200 responses/videocasts.show.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/401.json
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $videocast = $this->repository->findOrfail($id);

        activity('Videocast')->causedBy(request()->user())->log('Videocast consultado: '.$videocast->title);

        return response()->json(new Videocast($videocast));
    }


    public function store(VideocastStoreRequest $request) {
        request()->user()->authorizeRoles(['Administrador']);

        $videocast = $this->repository->create($request);

        activity('Videocast')->causedBy(request()->user())->log('Videocast salvo: '.$videocast->title);

        return response()->json(new Videocast($videocast), 201);
    }


    public function update(VideocastUpdateRequest $request, int $id) {
        request()->user()->authorizeRoles(['Administrador']);

        $videocast = $this->repository->findOrfail($id);
        $videocast = $this->repository->update($request, $videocast);

        activity('Videocast')->causedBy(request()->user())->log('Videocast alterado: '.$videocast->title);

        return response()->json(new Videocast($videocast));
    }


    public function destroy($id) {
        request()->user()->authorizeRoles(['Administrador']);

        $videocast = $this->repository->findOrfail($id);
        $this->repository->delete($videocast);

        activity('Videocast')->causedBy(request()->user())->log('Videocast deletado: '.$videocast->title);

        return response()->json(null, 204);
    }

    public function postRead(int $id)
    {
        $model = $this->repository->findOrfail($id);
        try {
            $read = $this->repository->read(
                $model,
                Auth::user()
            );
            activity('Videocast')->causedBy(request()->user())->log('Videocast lida ' . $model->title);

            return response()->json('ok', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 200);
        }
    }

    public function getUsersRead(int $id)
    {
        $model = $this->repository->findOrfail($id);

        $usersRead = $this->repository->countUsersRead($model);

        activity('Videocast')->causedBy(request()->user())->log('Contagem de acesso da Videocast ' . $model->title);

        return $usersRead;
    }

    public function getUsersUnRead(int $id)
    {
        $model = $this->repository->findOrfail($id);
        $read = $this->repository->countUsersRead($model);
        $usersRead = $this->repository->countUsersUnread($model, $read);

        activity('Videocast')->causedBy(request()->user())->log('Contagem de não acesso da Videocast ' . $model->title);

        return $usersRead;
    }

    public function getReportVideoCast(int $id)
    {
        request()->user()->authorizeRoles(['Administrador']);
        $model = $this->repository->findOrfail($id);

        $read = $this->repository->countUsersRead($model);
        $unread = $this->repository->countUsersUnread($model, $read);
        $percentage = $this->repository->percentageUsersRead($read, $unread);

        $model->load('user');

        return response()->json([
            'videocast' => new Videocast($model),
            'read' => $read,
            'unread' => $unread,
            'reading_percentage' => number_format($percentage, 2)
        ]);
    }

    public function findByUserRead(int $id, int $idUser) {
        $response = $this->repository->findByUserRead($id, $idUser);

        if (empty($response)) {
            return response()->json([
                'visualized' => false
            ]);
        } else {
            return response()->json([
                'visualized' => true
            ]);
        }
    }

    public function getUserReadView(int $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $model = $this->repository->findOrfail($id);

        $usersRead = $this->repository->usersReadView($model);

        return response()->json(
            UserSimplified::collection($usersRead)
        );
    }

    public function getUserUnreadView(int $id)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $model = $this->repository->findOrfail($id);

        $usersUnread = $this->repository->usersUnreadView($model);

        return response()->json(
            UserSimplified::collection($usersUnread)
        );
    }
}
