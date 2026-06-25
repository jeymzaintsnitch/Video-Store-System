<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public static function log(
        string $action,
        string $description,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        $user = Auth::user();

        AuditLog::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->name ?? 'System',
            'action'      => strtoupper($action),
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'description' => $description,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'created_at'  => now(),
        ]);
    }
}