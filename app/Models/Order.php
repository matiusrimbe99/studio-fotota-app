<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_order',
        'user_id',
        'packet_id',
        'studio_id',
        'shooting_date',
        'status_order_id',
        'payment_proof',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function packet()
    {
        return $this->belongsTo(Packet::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function statusOrder()
    {
        return $this->belongsTo(StatusOrder::class);
    }

    protected function paymentProof(): Attribute
    {
        return Attribute::make(
            get:fn($payment_proof) => asset('/storage/payment_proofs/' . $payment_proof),
        );
    }
}
