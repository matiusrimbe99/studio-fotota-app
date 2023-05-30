<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'whatsapp',
        'facebook',
        'instagram',
        'account_number',
        'name_on_account',
        'bank_name',
        'method_order',
    ];
}
