<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingDoc extends Model
{
    use HasFactory;

    protected $table = 'outgoing_docs';

    protected $fillable = [
        'store_id',
        'company_id',
        'user_id',
        'username',
        'doc_no',
        'doc_type_id',
        'products_ids',
        'to_contractor',
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
}
