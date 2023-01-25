<?php

namespace App\Http\Controllers\Api;

use App\ChristmasToken;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class ChristmasTokenController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, User $user)
    {
        if ($user->id !== auth()->user()->id && !auth()->user()->hasRole('Administrador')) {
            abort(403, 'This action is forbidden.');
        }

        $tokenData = ChristmasToken::with('user')->where('user_id', $user->id)->firstOrFail();

        activity('ChristmasToken')->causedBy(request()->user())->log('Acesso do token da cesta consultado: '.$tokenData->cpf);

        return response()->json([
            'token' => $tokenData->token,
            'cpf' => $tokenData->cpf,
            'user' => [
                'name' => $tokenData->user->name,
                'cpf' => $tokenData->user->cpf,
                'avatar' => $tokenData->user->avatar
            ]
        ]);
    }
}
