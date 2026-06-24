<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class InventoryItem extends Model
{
    use LogsActivity;
    protected $fillable = [
        'name', 'category', 'quantity', 'unit',
        'low_stock_threshold', 'unit_price', 'supplier', 'notes',
    ];

    protected $casts = [
        'quantity'            => 'decimal:2',
        'low_stock_threshold' => 'decimal:2',
        'unit_price'          => 'decimal:2',
    ];

    public function releases()
    {
        return $this->hasMany(InventoryRelease::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock())  return 'out_of_stock';
        if ($this->isLowStock())    return 'low_stock';
        return 'in_stock';
    }

    public function getStockBadgeColorAttribute(): string
    {
        return match($this->stock_status) {
            'out_of_stock' => 'bg-red-50 text-red-700',
            'low_stock'    => 'bg-amber-50 text-amber-700',
            default        => 'bg-green-50 text-green-700',
        };
    }

    public function getStockBadgeLabelAttribute(): string
    {
        return match($this->stock_status) {
            'out_of_stock' => 'Out of stock',
            'low_stock'    => 'Low stock',
            default        => 'In stock',
        };
    }

    // Scope for low/out of stock items
    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'low_stock_threshold');
    }
}