<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingDoc extends Model
{
    use HasFactory;

    protected $table = 'incoming_docs';

    protected $fillable = [
        'store_id',
        'company_id',
        'user_id',
        'username',
        'doc_no',
        'doc_type_id',
        'products_ids',
        'from_contractor',
        'sum',
        'currency',
        'count',
        'unit',
        'comment',
    ];
}
