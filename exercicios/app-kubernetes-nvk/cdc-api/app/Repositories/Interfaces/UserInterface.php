<?php
namespace App\Repositories\Interfaces;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

interface UserInterface extends AbstractInterface {
    public function create(UserStoreRequest $request);

    public function register(UserRegisterRequest $request);

    public function update(UserUpdateRequest $request, $user);

    public function delete($user);

    public function updatePassword($model, $password);

    public function checkTerms($user);

    public function approve($user);

    public function disapprove($user);

    public function storeAvatar($user, $request);

    public function getAlert();

    public function nearBirthday();

    public function getByCpf(string $cpf);
}
