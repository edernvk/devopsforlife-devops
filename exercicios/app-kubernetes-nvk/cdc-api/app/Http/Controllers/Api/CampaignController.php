<?php

namespace App\Http\Controllers\Api;

use App\Campaign;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CampaignController extends Controller
{
    public function getCampaignDetails(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        $this->checkCampaignStatus($campaign);

        return response()->json($campaign);
    }

    protected function checkCampaignStatus($campaign) {
        $campaignStart = Carbon::createFromFormat('Y-m-d', $campaign->entry_date);
        abort_if(Carbon::now()->lessThan($campaignStart->startOfDay()), 406, 'Esta campanha ainda não está ativa.');

        $campaignEnd = Carbon::createFromFormat('Y-m-d', $campaign->departure_date);
        abort_if(Carbon::now()->greaterThan($campaignEnd->endOfDay()), 406, 'Esta campanha já foi encerrada.');
    }
}
