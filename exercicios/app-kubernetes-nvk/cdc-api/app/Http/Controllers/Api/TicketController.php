<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\TicketUpdateRequest;
use App\Http\Requests\TicketStoreRequest;
use App\Ticket;
use App\User;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        activity('Ticket')->causedBy(request()->user())->log('Cupom não gerado: campanha encerrada');

        Validator::make($request->all(), [
            'valid_date' => 'required|before_or_equal:2020-10-16'
        ],
        [
            'required' => 'A campanha já foi encerrada.',
            'before_or_equal' => 'A campanha já foi encerrada.',
        ])->validate();

        // probably will not even hit it
        return response()->json([]);

//        $ticket = new Ticket();
//        $ticket->fill($request->validated());
//        $ticket->coupon = Str::uuid();
//        $ticket->save();
//        $ticket->load('city');
//
//        activity('Ticket')->causedBy(request()->user())->log('Cupom gerado: ' . $ticket->uuid);
//
//        return response()->json(new \App\Http\Resources\Ticket($ticket), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Ticket $ticket)
    {
        return response()->json(new \App\Http\Resources\Ticket($ticket), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeTicket(User $user)
    {
//        $fullUser = $user::with(['activeTicket', 'activeTicket.city'])->first();
        $user->load('activeTicket');
        $ticket = $user->activeTicket()->with('city')->first();

        if (!$ticket) {
            abort(404, 'Usuário informado não possui um cupom ativo no momento');
        }

        return response()->json(new \App\Http\Resources\Ticket($ticket), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TicketUpdateRequest  $request
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TicketUpdateRequest $request, Ticket $ticket)
    {
//        $ticket->fill($request->validated());
//        $ticket->save();
//        $ticket->load('city');
//
//        activity('Ticket')->causedBy(request()->user())->log('Cupom atualizado: ' . $ticket->uuid);
//
//        return response()->json(new \App\Http\Resources\Ticket($ticket), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
