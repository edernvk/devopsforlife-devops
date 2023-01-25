<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PasswordResetToken;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Providers\RouteServiceProvider;
use App\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    // use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    public function checkToken(Request $request) 
    {
        $request->validate([
            'token' => 'required|string|exists:password_reset_tokens,token',
        ]);

        $token = PasswordResetToken::where('token', $request->token)
            ->where('created_at', '>', Carbon::now()->subHour(5))
            ->first();
        abort_if(!$token, '', 401);

        $user = User::where('cpf', $token->cpf)->first();
        abort_if(!$user, '', 401);

        return response()->json($user, 200);
    }

    public function reset(Request $request) 
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|confirmed|string|min:8',
        ]);

        $token = PasswordResetToken::where('token', $request->token)->first();

        $user = User::where('cpf', $token->cpf)->first();

        if ($user->cpf === $token->cpf) {
            $user->password = Hash::make($request->password);
            $user->save();

            PasswordResetToken::where('token', $token->token)->delete();
            return response()->json('ok', 200);
        }

        return response()->json('Nao foi possivel alterar a conta', 403);
    }

}
