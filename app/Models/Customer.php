<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'address',
        'image',
        'nomor_hp',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get:fn($image) => asset('/storage/customers/' . $image),
        );
    }
}
