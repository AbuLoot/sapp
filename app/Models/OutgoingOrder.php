<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingOrder extends Model
{
    use HasFactory;

    protected $table = 'outgoing_orders';

    protected $fillable = [
        'company_id',
        'cashbook_id',
        'workplace_id',
        'user_id',
        'doc_no',
        'doc_type_id',
        'products_data',
        'contractor_type',
        'contractor_id',
        'operation_code',
        'sum',
        'currency',
        'count',
        'comment',
    ];

    public function cashDoc()
    {
        return $this->morphOne(CashDoc::class, 'order');
    }

    public function docType()
    {
        return $this->belongsTo('App\Models\DocType', 'doc_type_id');
    }
}
