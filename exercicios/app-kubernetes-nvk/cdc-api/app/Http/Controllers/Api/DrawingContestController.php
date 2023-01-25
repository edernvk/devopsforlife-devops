<?php

namespace App\Http\Controllers\Api;

use App\Campaign;
use App\DrawingContestCategory;
use App\DrawingContestPicture;
use App\DrawingContestVote;
use App\Http\Controllers\Controller;
use App\Http\Requests\DrawingContestSaveBatchVotesRequest;
use App\Http\Requests\DrawingContestSaveVoteRequest;
use App\User;
use Carbon\Carbon;

class DrawingContestController extends Controller
{
    public function getCampaignDetails(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)->first();

        $startDate = Carbon::createFromFormat('Y-m-d', $campaign->entry_date)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $campaign->departure_date)->endOfDay();

        abort_if(!Carbon::now()->betweenIncluded($startDate, $endDate), 406, 'Esta campanha está inativa.');

        return response()->json($campaign);
    }

    public function getAllChoices()
    {
        $categoriesWithPictures = DrawingContestCategory::with('pictures')->get();
        activity('DrawingContestVotes')->causedBy(request()->user())->log('Consultados categorias + imagens da votação');

        return response()->json($categoriesWithPictures);
    }

    public function getChoices()
    {
        $categoriesWithPictures = DrawingContestCategory::with([
            'pictures' => function($query) {
                $query->withCount('votes')->orderByDesc('votes_count')->limit(4);
            },
        ])->get();

        $selectedPictures = DrawingContestCategory::with([
            'pictures' => function($query) {
                $query->withCount('votes')
                    ->orderByDesc('votes_count')
                    ->whereIn('subscription', [324, 321, 351])
                    ->limit(1);
            },
        ])->whereHas('pictures', function($query) {
            $query->whereIn('subscription', [324, 321, 351]);
        })->get();

        $categoriesWithPictures->transform(function($category) use ($selectedPictures) {
            $aditionalPicture = $selectedPictures->find($category->id);

            if ($aditionalPicture) {
                $category->pictures->push($aditionalPicture->pictures->first());
            }

            return $category;
        });

        activity('DrawingContestVotes')->causedBy(request()->user())->log('Consultados categorias + imagens da votação (segunda etapa)');

        return response()->json($categoriesWithPictures);
    }

    public function getUserVotes(string $campaignStage, User $user)
    {
        // i dont like this, but it will get the job done
        $votesByUser = $user->drawingContestVotes()->where('campaign_stage', $campaignStage)->get();

        return response()->json($votesByUser);
    }

    public function saveVote(DrawingContestSaveVoteRequest $request)
    {
        $validated = $request->validated();

        $vote = DrawingContestVote::create([
            'category_id' => $validated['category_id'],
            'picture_id' => $validated['selected_picture_id'],
            'campaign_stage' => $validated['campaign_stage'],
            'user_id' => auth()->user()->id
        ]);

        return response()->json($vote, 201);
    }

    public function saveBatchVotes(DrawingContestSaveBatchVotesRequest $request)
    {
        $validated = $request->validated();

        $votes = [];
        foreach ($validated['votes'] as $vote) {
            $votes[] = DrawingContestVote::create([
                'category_id' => $vote['category_id'],
                'picture_id' => $vote['selected_picture_id'],
                'campaign_stage' => $vote['campaign_stage'],
                'user_id' => auth()->user()->id
            ]);
        }

        activity('DrawingContestVotes')->causedBy(request()->user())->log('Novos votos salvos na votação');

        return response()->json($votes);
    }
}
