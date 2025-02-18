<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'ml',
        'barcode_ean',
        'barcode_dun',
    ];
}
