<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'sort_id',
        'region_id',
        'currency_id',
        'title',
        'slug',
        'bin',
        'image',
        'about',
        'phones',
        'links',
        'emails',
        'legal_address',
        'actual_address',
        'is_supplier',
        'is_customer',
        'status',
    ];

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function stores()
    {
        return $this->hasMany('App\Models\Store');
    }

    public function cashbooks()
    {
        return $this->hasMany('App\Models\Cashbook');
    }

    public function bank_accounts()
    {
        return $this->hasMany('App\Models\BankAccount');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id');
    }

    public function storeContracts()
    {
        return $this->morphMany(StoreDoc::class, 'contractor');
    }

    public function cashContracts()
    {
        return $this->morphMany(CashDoc::class, 'contractor');
    }
}
