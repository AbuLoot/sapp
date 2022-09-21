<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocType extends Model
{
    use HasFactory;

    public function incomingDocs()
    {
        return $this->hasMany('App\Models\IncomingDoc');
    }

    public function outgoingDocs()
    {
        return $this->hasMany('App\Models\OutgoingDoc');
    }

    public function incomingOrders()
    {
        return $this->hasMany('App\Models\IncomingOrder');
    }

    public function outgoingOrders()
    {
        return $this->hasMany('App\Models\OutgoingOrder');
    }

    public function revisions()
    {
        return $this->hasMany('App\Models\Revision');
    }
}
