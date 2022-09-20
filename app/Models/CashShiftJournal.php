<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashShiftJournal extends Model
{
    use HasFactory;

    protected $table = 'cash_shift_journal';

    public function cashDoc()
    {
        return $this->morphOne(CashDoc::class, 'order');
    }
}
