<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingDoc extends Model
{
    use HasFactory;

    protected $table = 'incoming_docs';

    protected $fillable = [
        'company_id',
        'store_id',
        'user_id',
        'workplace_id',
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

    public function contractor()
    {
        return $this->morphTo();
    }

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
