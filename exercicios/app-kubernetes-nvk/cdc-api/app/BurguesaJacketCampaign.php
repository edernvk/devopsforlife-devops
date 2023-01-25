<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BurguesaJacketCampaign extends Model
{
    const JACKET_SIZES = [
        'PP',
        'P',
        'M',
        'G',
        'GG',
        'EXG',
    ];
    const MAX_INSTALLMENTS = 4;
    const CAMPAIGN_END_DATE = '2022-02-08';

    protected $table = 'campaigns_burguesa_jacket';

    // user_id
    // payment_agreement
    protected $fillable = [
        'jacket_1_size',
        'jacket_2_size',
        'installments_amount',
        'payment_agreement'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
