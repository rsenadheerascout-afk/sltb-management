<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected function logActivity(
        string $action,
        string $modelType,
        int $modelId,
        string $description = '',
        array $oldValues = [],
        array $newValues = []
    ): void {
        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'description' => $description,
            'old_values'  => $oldValues ?: null,
            'new_values'  => $newValues ?: null,
            'ip_address'  => request()->ip(),
        ]);
    }
}