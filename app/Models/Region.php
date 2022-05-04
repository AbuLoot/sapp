<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Kalnoy\Nestedset\NodeTrait;

class Region extends Model
{
    use NodeTrait;

    protected $table = 'regions';

    public $timestamps = false;

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function storages()
    {
        return $this->hasMany('App\Models\Storage');
    }
}
