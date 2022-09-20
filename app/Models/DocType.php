<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocType extends Model
{
    use HasFactory;

    public function IncomingDocs()
    {
        return $this->hasMany('App\Models\IncomingDoc');
    }

    public function OutgoingDocs()
    {
        return $this->hasMany('App\Models\OutgoingDoc');
    }
}
