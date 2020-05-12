<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feeder extends Model
{
    protected $guarded = [];

    public function part()
    {

        return $this->belongsTo('App\Part');

    }
}
