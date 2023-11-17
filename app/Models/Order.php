<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'offer_id',
        'quantity',
        'order_date'
    ];

    public function getIdAttribute($value)
    {
        return 'ORD' . strtoupper($value);
    }


    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }
}
