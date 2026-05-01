<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $guarded = [];

    /**
     * Boot the model and register event listeners.
     */
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->tracking_code = self::generateTrackingCode();
        });
    }

    /**
     * Generate a unique 5-character alphanumeric tracking code (uppercase).
     */
    public static function generateTrackingCode(): string
    {
        do {
            $code = strtoupper(Str::random(5));
        } while (self::where('tracking_code', $code)->exists());

        return $code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
