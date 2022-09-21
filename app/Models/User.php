<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function storeDocs()
    {
        return $this->hasOne('App\Models\StoreDoc');
    }

    public function workplaces()
    {
        return $this->hasMany('App\Models\Workplace');
    }

    public function storeContracts()
    {
        return $this->morphMany(StoreDoc::class, 'contractor');
    }

    public function cashContracts()
    {
        return $this->morphMany(CashDoc::class, 'contractor');
    }

    public function incomingDocs()
    {
        return $this->hasMany('App\Models\IncomingDoc');
    }

    public function outgoingDocs()
    {
        return $this->hasMany('App\Models\OutgoingDoc');
    }

    public function revisions()
    {
        return $this->hasMany('App\Models\Revision');
    }

    public function productsDrafts()
    {
        return $this->hasMany('App\Models\ProductDraft');
    }

    public function cashDocs()
    {
        return $this->hasOne('App\Models\CashDoc');
    }

    public function incomingOrders()
    {
        return $this->hasMany('App\Models\IncomingOrder');
    }

    public function outgoingOrders()
    {
        return $this->hasMany('App\Models\OutgoingOrder');
    }
}
