<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CupShirtCampaing extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'installments_amount',
        'payment_agreement',
    ];

    const CAMPAIGN_END_DATE = '2022-09-30';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            CupShirtProducts::class,
            'cupshirt_campaing_product',
            'campaing_id',
            'product_id'
        )->withPivot(['amount', 'size']);
    }
}
