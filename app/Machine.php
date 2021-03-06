<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
