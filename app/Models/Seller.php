<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $primaryKey = 'sellerId';

    // disable timestamps created_at & updated_at
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address'
    ];

    public function offers()
    {
        return $this->hasMany(Offer::class, 'sellerId');
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Offer::class, 'sellerId', 'offerId');
    }
}
