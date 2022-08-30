<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionDraft extends Model
{
    use HasFactory;

    protected $table = 'revision_drafts';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
