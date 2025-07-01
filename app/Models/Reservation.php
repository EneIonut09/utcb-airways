<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'flight_id',
        'booking_reference',
        'booking_date',
        'status',
        'booking_class',
        'total_price'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($reservation) {
            if (empty($reservation->booking_reference)) {
                $reservation->booking_reference = 'UTCB-' . strtoupper(Str::random(6));
            }
        });
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function getPassengerCountAttribute()
    {
        return $this->passengers()->count();
    }

    public function getPrimaryPassengerAttribute()
    {
        return $this->passengers()->first();
    }
}