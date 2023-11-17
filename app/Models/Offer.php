<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    //auto incrementing id is enabled by default
    public $incrementing = true;

    protected $fillable = [
        'product_id',
        'seller_id',
        'price',
        'condition', // 'new' or 'used'
        'availability' // 'in stock' or 'out of stock' => Stokta/
    ];

    public function getIdAttribute($value)
    {
        return 'OF' . strtoupper($value);
    }

    public function getConditionAttribute($value)
    {
        switch ($value) {
            case 'new':
                return 'Yeni';
                break;
            case 'used':
                return 'Kullanılmış';
                break;
            default:
                return 'Unknown';
                break;
        }
    }

    public function getAvailabilityAttribute($value)
    {
        switch ($value) {
            case 'in stock':
                return 'Stokta';
                break;
            case 'out of stock':
                return 'Stokta Yok';
                break;
            default:
                return 'Unknown';
                break;
        }
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'offer_id');
    }
}
