<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PushSendUsersRequest;
use App\Http\Requests\PushStoreRequest;
use App\Push;
use App\Services\PushService;
use App\User;
use Illuminate\Support\Facades\Auth;

class PushController extends Controller
{
    private $pushService;

    public function __construct(PushService $pushService)
    {
        $this->pushService = $pushService;
    }

    public function index()
    {
        $pushes = Push::latest()->get();

        return response()->json($pushes);
    }

    public function sendAll(PushStoreRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $validated = $request->validated();

        $this->pushService->sendToAll(
            $validated['title'],
            $validated['message'],
            $validated['url'],
            $validated['publish_datetime']
        );

        $push = Push::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => 'TODOS',
            'delivered' => $validated['delivered'],
            'user_id' => Auth::id()
        ]);

        return response()->json($push, 201);
    }

    public function sendExternalUser(PushSendUsersRequest $request)
    {
        request()->user()->authorizeRoles(['Administrador']);

        $validated = $request->validated();

        $userIds = User::whereIn('cpf', $validated['cpfs'])
            ->get('id')
            ->map(function ($user) {
                return (string) $user['id'];
            })->toArray();

        $this->pushService->sendToExternalUser(
            $validated['title'],
            $validated['message'],
            $validated['url'],
            $userIds,
            $validated['publish_datetime']
        );

        $push = Push::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'type' => 'USUARIOS',
            'delivered' => $validated['delivered'],
            'user_id' => Auth::id()
        ]);

        return response()->json($push, 201);
    }

    public function sendGroupUser()
    {
        request()->user()->authorizeRoles(['Administrador']);
    }
}
