<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\User;
use App\Repositories\Interfaces\UserInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

/**
 * @group Auth
 */
class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Login
     *
     * Oauth login returns user logged and access token
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam email string required The user email. Example: johndoe@casadiconti.com.br
     * @bodyParam password string required The correct user password. Example: senha@1234
     *
     * @responseFile 200 responses/auth.login.200.json
     * @responseFile 404 responses/404.json
     * @responseFile 401 responses/auth.login.401.json
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        if(!Auth::attempt(['cpf' => $request->cpf, 'password' => $request->password])) {
            return response()->json(['message' => 'Credenciais Inválidas'], 401);
        }

        if(auth()->user()->approved == null) {
            Auth::user()->OauthAccessToken()->delete();
            activity('Auth')->causedBy(request()->user())->log('Tentativa de login do sistema, por usuário não aprovado: '.$request->cpf);

            return response()->json(
                ['message' => 'Desculpe, você não possui permissão para acessar o sistema, verifique com um administrador e tente novamente'], 401
            );
        }

        $accessToken = auth()->user()->createToken('token')->accessToken;

        auth()->user()->asAuthenticated($request);

        activity('Auth')->causedBy(request()->user())->log('Login no sistema');

        return response()->json(['user' => new User(auth()->user()), 'token' => $accessToken]);
    }

    /**
     * Logout
     *
     * Oauth logout returns succes if auth user is logged out
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request) {
        abort_unless(auth()->user()->token()->revoke(), 503);

        activity('Auth')->causedBy(request()->user())->log('Logout do sistema');

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Register New Users
     *
     * Register new users without authorization
     *
     * @param  \Illuminate\Http\Request  $request
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: johndoe@casadiconti.com.br
     * @bodyParam registration string The company registration number of the user. Example: 99999
     * @bodyParam mobile string required The mobile of the user. Example: (99) 99999-9999
     * @bodyParam avatar string The avatar picture of the user. Example: /storage/user/asdjauisdhasud.jpg
     * @bodyParam city_id integer The city id number. Example: 4630
     * @bodyParam team_id integer The team id number. Example: 1
     * @bodyParam password string required The user access password. Example: senha@1234
     * @bodyParam password_confirmation required The user access password confirmation. Example: senha@1234
     *
     * @responseFile 201 responses/users.store.201.json
     * @responseFile 401 responses/401.json
     * @responseFile 404 responses/404.json
     * @responseFile 422 responses/users.store.422.json
     * @return \Illuminate\Http\Response
     */
    public function register(UserRegisterRequest $request) {
        $user = $this->userRepository->register($request);

        activity('User')->causedBy(request()->user())->log('Usuário registrado: '.$user->name);

        return response()->json(new User($user), 201);
    }
}
