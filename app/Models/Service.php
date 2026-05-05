<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];

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
     * Get the category options for services.
     */
    public static function categories(): array
    {
        return ['Special Treatment', 'Cleaning', 'Repair Treatment', 'Repaint Treatment'];
    }
}
