<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDoc extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
    }

    public function contractor()
    {
        return $this->morphTo();
    }

    public function doc_type()
    {
        return $this->belongsTo('App\Models\DocType', 'doc_type_id');
    }
}
