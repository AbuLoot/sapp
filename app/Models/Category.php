<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    protected $table = 'categories';

    public function cities()
    {
        return $this->hasMany('App\Models\City');
    }

    public function companies()
    {
        return $this->hasMany('App\Models\Company');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}