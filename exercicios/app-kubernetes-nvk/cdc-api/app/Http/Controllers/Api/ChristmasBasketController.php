<?php

namespace App\Http\Controllers\Api;

use App\ChristmasBasket;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChristmasBasketStoreRequest;

class ChristmasBasketController extends Controller
{

    public function retrieve () {
        $christmasBasketEntry = ChristmasBasket::where('user_id', auth()->user()->id)->first();
        abort_unless($christmasBasketEntry, 404, 'Usuário informado não possui uma resposta cadastrada.');

        return response()->json($christmasBasketEntry);
    }

    public function store(ChristmasBasketStoreRequest $request)
    {
        $validated = $request->validated();

        $christmasBasket = ChristmasBasket::create($validated);

        return response()->json($christmasBasket, 201);
    }

    /* for quick testing, will not go live */
    public function remove() {
        request()->user()->authorizeRoles(['Administrador', 'Tester_CestaNatal2021']);

        $christmasBasket = ChristmasBasket::where('user_id', auth()->user()->id)->firstOrFail();
        $christmasBasket->delete();

        return [];
    }
}
