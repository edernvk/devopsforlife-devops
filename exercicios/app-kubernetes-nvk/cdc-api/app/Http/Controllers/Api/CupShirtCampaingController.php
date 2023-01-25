<?php

namespace App\Http\Controllers\Api;

use App\CupShirtCampaing;
use App\CupShirtProducts;
use App\Http\Controllers\Controller;
use App\Http\Requests\CupShirtCampaingSaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CupShirtCampaingController extends Controller
{
    public function retrieveProducts()
    {
        $products = CupShirtProducts::all();

        return response()->json($products);
    }

    public function retrieve()
    {
        // Uncomment and change CAMPAIGN_END_DATE value on model for it to work
        $campaignEnd = Carbon::createFromFormat('Y-m-d', CupShirtCampaing::CAMPAIGN_END_DATE);
        abort_if(Carbon::today()->gt($campaignEnd), 406, 'Esta campanha já foi encerrada.');

        $burguesaJacketEntry = CupShirtCampaing::where('user_id', auth()->user()->id)->first();
        // abort_unless($burguesaJacketEntry, 404, 'Usuário informado não possui um pedido cadastrado.');

        activity('CupShirtCampaing')->causedBy(request()->user())->log('Pedido de Camiseta da Copa consultado: '. $burguesaJacketEntry->id);

        return response()->json($burguesaJacketEntry->load('products'));
    }

    public function saveCampaing(CupShirtCampaingSaveRequest $request)
    {
        $validated = $request->validated();

        $cupshirt = CupShirtCampaing::query()->updateOrCreate([
            'user_id' => auth()->id()
        ],[
            'total_amount' => $validated['total_amount'],
            'installments_amount' => $validated['installments_amount'],
            'payment_agreement' => $validated['payment_agreement'],
        ]);

        $this->saveProducts($validated, $cupshirt);

        activity('CupShirtCampaing')->causedBy(request()->user())->log('Pedido de Camiseta da Copa adicionado: '. $cupshirt->id);

        return response()->json($cupshirt);
    }

    private function saveProducts(array $validated, $cupshirt)
    {
        foreach ($validated['products'] as $product) {
            $cupshirt->products()->attach($product['id'], [
                'amount' => $product['amount'],
                'size' => $product['size']
            ]);
        }
    }
}
