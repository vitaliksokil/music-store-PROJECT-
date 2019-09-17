<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'price','photo'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
