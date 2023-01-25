<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\VaccineCampaing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccineCampaignController extends Controller
{
    public function retrieve () {
        $vaccine = VaccineCampaing::where('user_id', auth()->user()->id)->first();
        abort_unless($vaccine, 404, 'Usuário informado não possui uma resposta cadastrada.');

        activity('VaccineCampaing')->causedBy(request()->user())->log('Resposta campanha de vacinacao conferida');

        return response()->json($vaccine);
    }
    public function store(Request $request)
    {
        $request->validate([
            'confirmation' => 'required',
            'authorize' => 'required'
        ]);

        $vaccine = VaccineCampaing::create([
            'user_id' => Auth::user()->id,
            'confirmation' => $request->confirmation,
            'authorize' => $request->authorize
        ]);

        activity('VaccineCampaing')->causedBy(request()->user())->log('Resposta campanha de vacinacao salva');

        return response($vaccine, 201);
    }
}
