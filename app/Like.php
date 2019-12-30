<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable=[
      'like','user_id','feedback_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function feedback(){
        return $this->belongsTo(Feedback::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

}
