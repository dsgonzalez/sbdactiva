<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    protected $table = 'towns';

   // protected $fillable = ['province_id','province_code','DC','name'];

    public function user()
    {
        return $this->hasMany('User');
    }

    public function province()
    {
        return $this->belongsTo('App\Province', 'province_id');
    }

    public function getProvinceTown()
    {
        return Province::where('id',$this->province_id)->first()->name;
    }
}
