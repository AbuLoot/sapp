<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    use HasFactory;

    protected $table = 'revisions';

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
