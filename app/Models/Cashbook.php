<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashbook extends Model
{
    use HasFactory;

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id');
    }

    public function workplaces()
    {
        return $this->morphMany(Workplace::class, 'workplace');
    }

    public function cashDocs()
    {
        return $this->hasMany('App\Models\CashDoc');
    }
}
