<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingDoc extends Model
{
    use HasFactory;

    protected $table = 'outgoing_docs';

    protected $fillable = [
        'company_id',
        'store_id',
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
        'unit',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function storeDoc()
    {
        return $this->morphOne(StoreDoc::class, 'doc');
    }

    public function docType()
    {
        return $this->belongsTo('App\Models\DocType', 'doc_type_id');
    }
}
