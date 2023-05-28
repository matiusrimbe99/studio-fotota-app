<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{
    use HasFactory;

    protected $fillable = [
        'packet_name',
        'description',
        'price',
        'image',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get:fn($image) => asset('/storage/packets/' . $image),
        );
    }
}
