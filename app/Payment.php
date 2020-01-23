<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'amount',
        'payer_purse',
        'payer_wm',
        'created_at',
        'updated_at',];
}
