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
        'slug',
        'title',
        'phones',
        'address',
        'legal_address',
        'image',
        'is_supplier',
        'is_customer',
        'lang',
        'status',
        'sort_id'
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
}
