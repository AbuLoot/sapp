<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDoc extends Model
{
    use HasFactory;

    protected $table = 'store_docs';

    protected $fillable = [
        'store_id',
        'company_id',
        'user_id',
        'doc_type',
        'doc_id',
        'order_id',
        'products_data',
        'contractor_type',
        'contractor_id',
        'incoming_amount',
        'outgoing_amount',
        'count',
        'sum',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'store_id');
    }

    public function doc()
    {
        return $this->morphTo();
    }

    public function contractor()
    {
        return $this->morphTo();
    }
}
