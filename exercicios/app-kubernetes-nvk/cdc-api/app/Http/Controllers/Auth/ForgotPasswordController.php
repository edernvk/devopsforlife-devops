<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\PasswordResetToken;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    public function store(Request $request) 
    {
        $request->validate([
            'cpf' => 'required|string|exists:users,cpf'
        ], [
            'cpf.exists' => 'Esse CPF nao esta cadastrado'
        ]);
        // activity('Email')->causedBy(request()->user())->log('Usuario esqueceu a senha: '. now());

        $user = User::where('cpf', $request->cpf)->first();
        if ($user->email === null) {
            abort(404, "Nao foi encontrado seu email em nossos registros");
        }

        $token = PasswordResetToken::create([
            'token'   => Str::random(16),
            'cpf'   => $user->cpf,
        ]);

        try {

            Mail::to($user->email)->send(
                    new ForgotPasswordMail('http://app.casadiconti.com.br/recuperar-senha?token='. $token->token)
                );
            return response()->json([
                'message' => 'Email enviado com sucesso, verifique sua caixa de entrada'
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'nao foi possivel enviar o email, tente novamente'
            ], 400);
        }
    }
}
