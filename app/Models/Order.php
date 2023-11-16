<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'orderId';

    // disable timestamps created_at & updated_at
    public $timestamps = false;

    protected $fillable = [
        'offerId',
        'quantity',
        'orderDate'
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offerId');
    }

}
