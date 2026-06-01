<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRelease extends Model
{
    protected $fillable = [
        'inventory_item_id', 'breakdown_report_id',
        'released_by', 'quantity_released', 'reason', 'notes',
    ];

    protected $casts = [
        'quantity_released' => 'decimal:2',
    ];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function breakdownReport()
    {
        return $this->belongsTo(BreakdownReport::class);
    }

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by');
    }
}