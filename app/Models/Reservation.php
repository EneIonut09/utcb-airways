<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'flight_id',
        'passenger_name',
        'passenger_email',
        'passenger_phone',
        'booking_date',
        'status',
        'total_price',
        'number_of_passengers',
        'special_requests'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}