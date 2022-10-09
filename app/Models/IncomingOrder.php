<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingOrder extends Model
{
    use HasFactory;

    protected $table = 'incoming_orders';

    public function contractor()
    {
        return $this->morphTo();
    }

    public function cashDoc()
    {
        return $this->morphOne(CashDoc::class, 'order');
    }

    public function docType()
    {
        return $this->belongsTo('App\Models\DocType', 'doc_type_id');
    }
}
