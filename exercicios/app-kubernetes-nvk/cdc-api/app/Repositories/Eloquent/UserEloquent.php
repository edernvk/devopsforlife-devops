<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\UserRegisterRequest;
use App\Repositories\Interfaces\UserInterface;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Role;
use App\User;
use App\Http\Resources\UserSimplified;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserEloquent extends AbstractEloquent implements UserInterface {

    public function __construct() {
        parent::__construct('User');
    }

    public function all() {
        return UserSimplified::collection(User::all());
    }

    public function create(UserStoreRequest $request) {
        $user = $request->validated();
        $user['password'] = Hash::make($user['password']);
        $user['approved'] = Carbon::now();

        $user = User::create($user);

        $roles = collect($request->input('roles'));
        if($roles->isNotEmpty())
            $user->roles()->sync($roles);

        return $user;
    }

    public function register(UserRegisterRequest $request) {
        $user = $request->all();
        $user['password'] = Hash::make($user['password']);
        $user = User::create($user);
        $role = Role::where('name', 'Colaborador')->first();
        $user->roles()->attach($role);
        return $user;
    }

    public function update(UserUpdateRequest $request, $model) {
        $model->update($request->validated());

        $roles = collect($request->input('roles'));
        if($roles->isNotEmpty())
            $model->roles()->sync($roles);

        return $model;
    }

    public function delete($model) {
        $model->delete();
    }

    public function updatePassword($model, $password) {
        $model->password = Hash::make($password);

        if($model->first_time === null)
            $model->first_time = Carbon::now();

        $model->save();

        return $model;
    }

    public function checkTerms($user) {
        $user->allow_terms = Carbon::now();
        $user->save();
        return $user;
    }

    public function approve($user) {
        $user->approved = Carbon::now();
        $user->save();

        return $user;
    }

    public function disapprove($user) {
        $user->approved = null;
        $user->save();
        $user->load('tokens');

        $user->tokens->each(function ($token) {
            if (!$token->revoked) {
                $token->revoke();
            }
        });

        return $user;
    }

    public function storeAvatar($user, $request) {

        if($request->hasFile('avatar')) {
            $filePath = $request->file('avatar')->move(
                storage_path('app/public/users-avatars'),
                time().rand(1,100).$request->file('avatar')->getClientOriginalName()
            )->getFilename();

            $path = Storage::url('users-avatars/'.$filePath);

            $user->avatar = $path;
            $user->save();

            return $path;
        } else {
            return null;
        }
    }

    public function getAlert() {
        $users = User::whereNull('last_login_at')
            ->orWhereDate('last_login_at', '<', Carbon::now()->subDays(15))
            ->get();

        return $users;
    }

    public function nearBirthday() {
        $today = Carbon::today()->format('d-m');
        $tomorrow = Carbon::tomorrow()->format('d-m');

        $todayUsers = User::approved()->where('birth_date', $today)->get(['name', 'office', 'workplace', 'avatar']);
        $tomorrowUsers = User::approved()->where('birth_date', $tomorrow)->get(['name', 'office', 'workplace', 'avatar']);

        return [
            'today' => $todayUsers,
            'tomorrow' => $tomorrowUsers
        ];
    }

    public function getByCpf(string $cpf) {
        return User::where('cpf', $cpf)->first();
    }
}
