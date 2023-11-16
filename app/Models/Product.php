<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'productId';

    // disable timestamps created_at & updated_at
    public $timestamps = false;

    protected $fillable = [
        'name',
        'category',
        'price',
    ];
}
