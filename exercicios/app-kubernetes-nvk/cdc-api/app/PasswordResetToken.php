<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'token',
        'cpf'
    ];
}
