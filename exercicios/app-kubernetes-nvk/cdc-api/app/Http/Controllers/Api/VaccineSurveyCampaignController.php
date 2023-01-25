<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\VaccineSurveyCampaign;
use App\Http\Requests\VaccineSurveyCampaignStoreRequest;

class VaccineSurveyCampaignController extends Controller
{

    public function store(VaccineSurveyCampaignStoreRequest $request)
    {
//        $this->checkUserPermission();
        $this->checkCampaignEnd();

        $validated = $request->validated();
        $vaccineSurvey = new VaccineSurveyCampaign;
        $vaccineSurvey->fill($validated);
        $vaccineSurvey->user()->associate($validated['user_id']);
        $vaccineSurvey->save();

        activity('VaccineSurveyCampaign')->causedBy(request()->user())->log('Nova resposta de Pesquisa - Vacinação COVID-19: '. $vaccineSurvey->id);

        return response()->json($vaccineSurvey);
    }

    public function retrieve()
    {
//        $this->checkUserPermission();
        $this->checkCampaignEnd();

        $vaccineSurveyEntry = VaccineSurveyCampaign::where('user_id', auth()->user()->id)->first();
        abort_unless($vaccineSurveyEntry, 404, 'Usuário informado não possui uma resposta cadastrada.');

//        activity('VaccineSurveyCampaign')->causedBy(request()->user())->log('Resposta de Pesquisa - Vacinação COVID-19 consultado: '. $burguesaJacketEntry->id);

        return response()->json($vaccineSurveyEntry);
    }

    /* for quick testing, will not go live */
    public function remove() {

        $this->checkUserPermission();

        $vaccineSurvey = VaccineSurveyCampaign::where('user_id', auth()->user()->id)->firstOrFail();
        $vaccineSurvey->delete();

        return [];
    }

    private function checkUserPermission() {
        request()->user()->authorizeRoles(['Administrador', 'Tester_PesquisaVacina']);
    }

    private function checkCampaignEnd() {
        $campaignEnd = Carbon::createFromFormat('Y-m-d', VaccineSurveyCampaign::CAMPAIGN_END_DATE);
        abort_if(Carbon::today()->gt($campaignEnd), 406, 'Esta campanha já foi encerrada.');
    }
}
