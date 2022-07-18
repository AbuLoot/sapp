<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocType extends Model
{
    use HasFactory;

    public function store_docs()
    {
        return $this->hasMany('App\Models\StoreDoc');
    }
}
