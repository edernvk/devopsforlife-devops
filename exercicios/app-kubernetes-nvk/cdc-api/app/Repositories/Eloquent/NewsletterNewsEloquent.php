<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\NewsletterNewsInterface;
use App\Http\Requests\NewsletterNewsStoreRequest;
use App\Http\Requests\NewsletterNewsUpdateRequest;
use App\NewsletterNews;
use App\StatusMessage;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class NewsletterNewsEloquent extends AbstractEloquent implements NewsletterNewsInterface {

    public function __construct() {
        parent::__construct('NewsletterNews');
    }

    public function all() {
        $this->handlePublished();
        return $this->model::latest()
        ->when(request()->has('search'), function ($query) {
            $query->where('title', 'like', '%' . request()->query('search') . '%')
                ->orWhere('content', 'like', '%' . request()->query('search') . '%');
        })->get();
    }

    public function publisheds() {
        $this->handlePublished();

        return $this->model::latest()
            ->where('status_id', StatusMessage::PUBLISHED)->get();
    }

    public function recent() {
        $this->handlePublished();
        return $this->model::latest()
            ->take(3)
            ->where('is_active', true)
            ->where('status_id', StatusMessage::PUBLISHED)
            ->get();
    }

    public function create(NewsletterNewsStoreRequest $request) {
        return $this->model::create($request->validated());
    }

    public function update(NewsletterNewsUpdateRequest $request, $model) {
        $model->update($request->validated());
        return $model;
    }

    public function delete($model) {
        $model->delete();
    }

    public function isActives()
    {
        $this->handlePublished();

        return $this->model::latest()
            ->where('is_active', true)
            ->where('status_id', StatusMessage::PUBLISHED)
            ->get();
    }

    public function changeStatus($model)
    {
        return $model->changeStatus();
    }

    public function findContrast()
    {
        return NewsletterNews::where('contrast', true)->latest()->first();
    }

    public function like($model, $user)
    {
        $userAlreadyRead = $model->usersLike()
            ->wherePivot('user_id', $user->id)
            ->first();

        if ($userAlreadyRead) {
            throw new Exception("User Already Liked");
        }

        return $model->usersLike()
            ->attach($user->id, [
                'like' => Carbon::now()
            ]);
    }

    public function countUsersLike($model)
    {
        return $model->usersLike()->count();
    }

    public function findByUserRead($id, $idUser) {
        $results = DB::select('select * from newsletter_like where user_id = :idUser and newsletter_news_id = :id', ['id' => $id, 'idUser' => $idUser]);
        return $results;
    }

    public function deleteLike($id, $idUser) {
        $results = DB::select('delete from newsletter_like where user_id = :idUser and newsletter_news_id = :id', ['id' => $id, 'idUser' => $idUser]);
        return $results;
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

    public function checkContrast(NewsletterNews $model)
    {
        return $model->markContrast();
    }

    /**
     * pegar todos os horarios de leitura
     * varificar quais horario mais proximos
     * retornar os horarios mais acessados primeiro
     * pegar quantos acessaram por horarios
     * contar quantos acessaram durante aquele horario
     */
    public function taxOpened($model)
    {

    }

    private function handlePublished(): void
    {
        $newsletters = NewsletterNews::where('status_id', StatusMessage::SCHEDULED)->get();
        $newsletters->each(function ($newsletter) {
            $currentDate = now()->format('Y-m-d H:i:s');
            if ($newsletter->publish_datetime <= $currentDate) {
                $newsletter->update(['status_id' => StatusMessage::PUBLISHED]);
            }
        });
    }
}
