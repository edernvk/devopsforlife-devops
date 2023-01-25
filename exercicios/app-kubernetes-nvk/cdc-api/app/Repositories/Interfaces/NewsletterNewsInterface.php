<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\NewsletterNewsStoreRequest;
use App\Http\Requests\NewsletterNewsUpdateRequest;
use App\NewsletterNews;

interface NewsletterNewsInterface extends AbstractInterface {
    public function create(NewsletterNewsStoreRequest $request);

    public function recent();

    public function publisheds();

    public function update(NewsletterNewsUpdateRequest $request, $newsletternews);

    public function delete($newsletternews);

    public function changeStatus($model);

    public function isActives();

    public function like($model, $user);

    public function countUsersLike($model);

    public function findByUserRead($id, $idUser);

    public function deleteLike($id, $idUser);

    public function read($model, $user);

    public function countUsersRead($model);

    public function countUsersUnread($model, $reads);

    public function usersReadView($model);

    public function findContrast();

    public function usersUnreadView($model);

    public function percentageUsersRead($model, $reads);

    public function checkContrast(NewsletterNews $model);

    public function taxOpened($model);
}
