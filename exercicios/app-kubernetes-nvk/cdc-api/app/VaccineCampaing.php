<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VaccineCampaing extends Model
{
    protected $fillable = [
        'user_id', 'authorize', 'confirmation'
    ];

    protected $casts = [
        'authorize' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
