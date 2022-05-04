<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
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
}
