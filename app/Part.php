<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $guarded = [];

    ////do not delete below for test
    // public function feeders()
    // {
    //     return $this->hasMany('App\Feeder');
    // }

    // public function feeders()
    // {
    //     return $this->belongsToMany('App\Feeder');
    // }
}
