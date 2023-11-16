<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $primaryKey = 'offerId';

    // disable timestamps created_at & updated_at
    public $timestamps = false;

    protected $fillable = [
        'productId',
        'sellerId',
        'price',
        'condition',
        'availability'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productId');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'sellerId');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'offerId');
    }
}
