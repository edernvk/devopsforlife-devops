<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VaccineSurveyCampaign extends Model
{
    const BOOLEAN_OPTIONS = ['yes', 'no'];
    const BOOLEAN_OPTIONS_EXTRA = ['yes', 'no', 'n/a'];
    const CAMPAIGN_END_DATE = '2021-08-30'; // confirmed

    protected $table = 'campaigns_vaccine_survey';

    protected $fillable = [
        'local_age_reached',
        'first_dose',
        'second_dose'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
