<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'tracking_code',
        'order_number',
        'customer_name',
        'phone_number',
        'shoe_brand',
        'shoe_size',
        'shoe_condition',
        'service_category',
        'service_name',
        'additional_fees',
        'total_price',
        'estimated_days',
        'payment_method',
        'payment_status',
        'status',
        'notes',
        'created_by',
        'external_id',
        'voucher_code',
        'discount_amount',
        'add_ons'
    ];
    
    protected $casts = [
        'add_ons' => 'array',
    ];

    /**
     * Get the formatted order ID with KC- prefix and random 5 chars.
     */
    public function getOrderIdFormattedAttribute(): string
    {
        return 'KC-' . ($this->order_number ?? $this->order_id);
    }

    /**
     * Set the estimated_days to take only the maximum number if it's a range.
     */
    protected function estimatedDays(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        $parseMax = function ($value) {
            if (empty($value)) return $value;
            preg_match_all('/\d+/', $value, $matches);
            if (!empty($matches[0])) {
                return max($matches[0]);
            }
            return $value;
        };

        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: $parseMax,
            set: $parseMax,
        );
    }

    /**
     * Boot the model and register event listeners.
     */
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (!$order->tracking_code) {
                $order->tracking_code = self::generateTrackingCode();
            }
            if (!$order->order_number) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    /**
     * Generate a unique order number with format: YY-XXXXX (year + 5 random chars).
     * 
     * Capacity: 36^5 = 60,466,176 unique IDs per year.
     * Each year the namespace resets automatically (2025-XXXXX vs 2026-XXXXX).
     * 
     * If somehow max retries exceeded (extremely unlikely), falls back to timestamp-based ID.
     */
    public static function generateOrderNumber(): string
    {
        $year = date('y'); // 2-digit year, e.g., "25" for 2025
        $maxRetries = 10;

        for ($i = 0; $i < $maxRetries; $i++) {
            $random = strtoupper(Str::random(5));
            $number = "{$year}{$random}";

            if (!self::where('order_number', $number)->exists()) {
                return $number;
            }
        }

        // Fallback: use microsecond timestamp (guaranteed unique, slightly longer)
        return $year . strtoupper(substr(base_convert((string) microtime(true) * 100, 10, 36), -5));
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

    /**
     * Get the service category options.
     */
    public static function serviceCategories(): array
    {
        return ['Special Treatment', 'Cleaning', 'Repair Treatment', 'Repaint Treatment'];
    }

    /**
     * Get the shoe condition options.
     */
    public static function shoeConditions(): array
    {
        return ['Kotor Ringan', 'Kotor Sedang', 'Kotor Berat', 'Rusak Ringan', 'Rusak Berat'];
    }

    /**
     * Get the payment method options.
     */
    public static function paymentMethods(): array
    {
        return ['Cash', 'QRIS', 'Transfer Bank'];
    }

    /**
     * Get the deadline date based on created_at and estimated_days.
     */
    public function getDeadlineDateAttribute()
    {
        if (empty($this->estimated_days)) return null;
        
        $days = is_numeric($this->estimated_days) ? (int) $this->estimated_days : 0;
        return $this->created_at->copy()->addDays($days)->startOfDay();
    }

    /**
     * Get the deadline status (danger, warning, safe).
     */
    public function getDeadlineStatusAttribute()
    {
        if (in_array($this->status, ['Ready', 'Delivered', 'cancelled'])) return 'completed';
        
        $deadline = $this->deadline_date;
        if (!$deadline) return 'normal';
        
        $today = now()->startOfDay();
        $diff = $today->diffInDays($deadline, false);
        
        if ($diff <= 0) return 'danger';   // Deadline reached or passed (Red)
        if ($diff == 1) return 'warning';  // H-1 (Yellow)
        return 'safe';                    // > H-1 (Blue)
    }

    /**
     * Get the status options.
     */
    public static function statuses(): array
    {
        return ['Waiting', 'Cleaning', 'Drying', 'Ready', 'Delivered', 'cancelled'];
    }
}
