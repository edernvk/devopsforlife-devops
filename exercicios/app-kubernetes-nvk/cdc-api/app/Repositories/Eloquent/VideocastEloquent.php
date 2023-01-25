<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\VideocastInterface;
use App\Http\Requests\VideocastStoreRequest;
use App\Http\Requests\VideocastUpdateRequest;
use App\Videocast;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class VideocastEloquent extends AbstractEloquent implements VideocastInterface {

    public function __construct() {
        parent::__construct('Videocast');
    }

    /**
     * @param string $order[optional] `date` column order_by either `asc` or `desc`
     * @return mixed
     */
    public function all($order = 'desc') {
        return $this->model::latest()->get();
    }

    /**
     * @param string $order[optional] `date` column order_by either `asc` or `desc`
     * @return mixed
     */
    // @Override
    public function paginate($order = 'desc') {
        return $this->model::orderBy('date', $order)->paginate();
    }

    public function create(VideocastStoreRequest $request) {
        return Videocast::create($request->validated());
    }

    public function update(VideocastUpdateRequest $request, $model) {
        $model->update($request->validated());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }

    public function read($model, $user)
    {
        $userAlreadyRead = $model->users()
            ->wherePivot('user_id', $user->id)
            ->first();

        if ($userAlreadyRead) {
            throw new Exception("User Already Read");
        }

        return $model->users()
            ->attach($user->id, [
                'read' => Carbon::now()
            ]);
    }

    public function countUsersRead($model)
    {
        return $model->users()->count();
    }

    public function countUsersUnread($model, $read)
    {
        $all = User::whereNotNull('approved')->count();

        return $all - $read;
    }

    public function usersReadView($model)
    {
        return $model->users()->get();
    }

    public function usersUnreadView($model)
    {
        $users = User::whereNotNull('approved')->get();

        $reads = $model->users()->get();

        $unread = collect($users)->merge($reads)
            ->filter(function ($data)  use ($reads) {
                foreach ($reads as $read) {
                    return $data->id !== $read->id;
                }
            });


        return $unread;
    }

    public function percentageUsersRead($read, $unread)
    {
        return $read != 0 || $unread != 0
            ? ($read / $unread) * 100
            : 0;
    }

    public function findByUserRead($id, $idUser) {
        $results = DB::select('select * from videocast_user where user_id = :idUser and videocast_id = :id', ['id' => $id, 'idUser' => $idUser]);
        return $results;
    }
}
