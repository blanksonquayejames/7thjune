<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'discount_percentage', 'discount_start', 'discount_end',
        'stock', 'image', 'is_active', 'is_hot', 'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_start' => 'datetime',
        'discount_end' => 'datetime',
        'is_active' => 'boolean',
        'is_hot' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Check if the product currently has an active discount.
     */
    public function hasActiveDiscount(): bool
    {
        if (!$this->discount_percentage || $this->discount_percentage <= 0) {
            return false;
        }

        $now = now();

        if ($this->discount_start && $now < $this->discount_start) {
            return false;
        }

        if ($this->discount_end && $now > $this->discount_end) {
            return false;
        }

        return true;
    }

    /**
     * Get the discounted price (returns original price if no active discount).
     */
    public function getDiscountedPriceAttribute(): float
    {
        if (!$this->hasActiveDiscount()) {
            return (float) $this->price;
        }

        return round((float) $this->price * (1 - $this->discount_percentage / 100), 2);
    }

    /**
     * Get the savings amount.
     */
    public function getSavingsAttribute(): float
    {
        return round((float) $this->price - $this->discounted_price, 2);
    }
}
