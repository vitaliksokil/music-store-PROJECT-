<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'photo'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function category()
    {
        return $this->belongsToMany('App\Category');
    }
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class)->orderBy('created_at','desc');
    }
}
