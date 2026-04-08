<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory; // see on vajalik, et saaksime factory-eid kasutada

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'payment_method',
        'payment_status',
        'stripe_session_id',
        'total_amount',
    ];
}
