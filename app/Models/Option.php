<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'options';

    public $timestamps = false;

    public function products()
    {
    	return $this->belongsToMany('App\Models\Product', 'product_option');
    }
}
