<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title', 'photo', 'parent_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function children()
    {
        return $this->hasMany(self::class,'parent_id');
    }
    public function products(){
        return $this->belongsToMany('App\Product');
    }
}
