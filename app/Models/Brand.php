<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'description',
        'about',
        'address',
        'image',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get:fn($image) => asset('/storage/brands/' . $image),
        );
    }
}
