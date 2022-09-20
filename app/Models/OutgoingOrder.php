<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingOrder extends Model
{
    use HasFactory;

    protected $table = 'outgoing_orders';

    public function cashDoc()
    {
        return $this->morphOne(CashDoc::class, 'order');
    }
}
