<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function productname()
    {
        return $this->belongsTo('App\Productname');
    }

    public function machine()
    {
        return $this->belongsTo('App\Machine');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function feeders()
    {
        return $this->hasMany('App\Feeder')->orderBy('feeder_number');///////
    }
}
