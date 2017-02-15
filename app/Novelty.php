<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Novelty extends Model
{
    protected $table = 'novelties';

    protected $fillable = ['title','text','user_id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
