<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PaycheckAccess extends Model
{
    protected $table = 'paycheck_access';

    protected $guarded = [];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }


    /* accessor and mutator to encrypt/decrypt data */

    public function setEmailAttribute($value) {
        $this->attributes['email'] = Crypt::encryptString($value);
    }

    public function getEmailAttribute($value) {
        return Crypt::decryptString($value);
    }

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = ($value) ? Crypt::encryptString($value) : null;
    }

    public function getPasswordAttribute($value) {
        return ($value) ? Crypt::decryptString($value) : null;
    }
}
