<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $guarded = [];

    public function feeders()
    {
        return $this->hasMany('App\Feeder');
    }

}
