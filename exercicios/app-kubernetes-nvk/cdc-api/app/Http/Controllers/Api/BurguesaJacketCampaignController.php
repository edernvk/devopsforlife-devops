<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\BurguesaJacketCampaign;
use App\Http\Requests\BurguesaJacketCampaignStoreRequest;
use App\Http\Controllers\Controller;

class BurguesaJacketCampaignController extends Controller
{

    public function store(BurguesaJacketCampaignStoreRequest $request)
    {
        // Uncomment and change CAMPAIGN_END_DATE value on model for it to work
        $campaignEnd = Carbon::createFromFormat('Y-m-d', BurguesaJacketCampaign::CAMPAIGN_END_DATE);
        abort_if(Carbon::today()->gt($campaignEnd), 406, 'Esta campanha já foi encerrada.');

        $validated = $request->validated();
        $burguesaJacket = new BurguesaJacketCampaign;
        $burguesaJacket->fill(array_merge($validated, [ 'payment_agreement' => now()->toDateTimeString() ]));
        $burguesaJacket->user()->associate($validated['user_id']);
        $burguesaJacket->save();

        activity('BurguesaJacketCampaign')->causedBy(request()->user())->log('Novo pedido de Jaqueta Burguesa: '. $burguesaJacket->id);

        return response()->json($burguesaJacket);
    }

    public function retrieve()
    {
        // Uncomment and change CAMPAIGN_END_DATE value on model for it to work
        $campaignEnd = Carbon::createFromFormat('Y-m-d', BurguesaJacketCampaign::CAMPAIGN_END_DATE);
        abort_if(Carbon::today()->gt($campaignEnd), 406, 'Esta campanha já foi encerrada.');

        $burguesaJacketEntry = BurguesaJacketCampaign::where('user_id', auth()->user()->id)->first();
        abort_unless($burguesaJacketEntry, 404, 'Usuário informado não possui um pedido cadastrado.');

        activity('BurguesaJacketCampaign')->causedBy(request()->user())->log('Pedido de Jaqueta Burguesa consultado: '. $burguesaJacketEntry->id);

        return response()->json($burguesaJacketEntry);
    }

    /* for quick testing, will not go live */
//    public function remove() {
//        request()->user()->authorizeRoles(['Administrador', 'Tester_JaquetaBurguesa']);
//
//        $burguesaJacket = BurguesaJacketCampaign::where('user_id', auth()->user()->id)->firstOrFail();
//        $burguesaJacket->delete();
//
//        return [];
//    }

}
