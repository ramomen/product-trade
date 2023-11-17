<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
    ];

    // get ID attribute
    public function getIdAttribute($value)
    {
        return 'P' . strtoupper($value);
    }
}
