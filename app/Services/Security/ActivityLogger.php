<?php

namespace App\Services\Security;

use App\ActivityLog;
use App\SecurityAlert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Throwable;

class ActivityLogger
{
    /**
     * Record a general business or model activity.
     */
    public static function log(
        string $action,
        string $description,
        ?Model $entity = null,
        ?array $changes = null,
        string $severity = 'info',
        bool $isSuspicious = false
    ): ?ActivityLog {
        try {
            $user = Auth::user();
            $userId = $user?->id;
            $officeId = $user?->office_id;

            $sanitizedChanges = $changes !== null
                ? SensitiveDataRedactor::redact($changes)
                : null;

            $log = ActivityLog::create([
                'user_id' => $userId,
                'office_id' => $officeId,
                'action' => $action,
                'entity_type' => $entity ? get_class($entity) : null,
                'entity_id' => $entity?->getKey(),
                'description' => SensitiveDataRedactor::redactString($description),
                'changes' => $sanitizedChanges,
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'user_agent' => substr((string)Request::userAgent(), 0, 255),
                'request_url' => substr((string)Request::fullUrl(), 0, 1000),
                'request_method' => Request::method() ?? 'CLI',
                'severity' => $severity,
                'is_suspicious' => $isSuspicious,
                'created_at' => now(),
            ]);

            // Mirror to file log channel for compliance/audit trail
            $fileChannel = $severity === 'security' || $isSuspicious ? 'security' : 'audit';
            Log::channel($fileChannel)->info($description, [
                'activity_id' => $log->id,
                'action' => $action,
                'user_id' => $userId,
                'office_id' => $officeId,
                'entity' => $entity ? (get_class($entity) . '#' . $entity->getKey()) : null,
                'ip' => Request::ip(),
            ]);

            return $log;
        } catch (Throwable $e) {
            // Never let logging failure break user transactions
            Log::error('Failed to write activity log: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Record an authentication event (login, failed login, logout, etc.).
     */
    public static function logAuth(
        string $action,
        string $description,
        ?int $userId = null,
        ?string $username = null,
        string $severity = 'info',
        bool $isSuspicious = false,
        ?array $extra = null
    ): ?ActivityLog {
        try {
            $user = Auth::user();
            $effectiveUserId = $userId ?? $user?->id;
            $officeId = $user?->office_id;

            $payload = [
                'username' => $username,
            ];
            if ($extra) {
                $payload = array_merge($payload, $extra);
            }

            $sanitizedChanges = SensitiveDataRedactor::redact($payload);

            $log = ActivityLog::create([
                'user_id' => $effectiveUserId,
                'office_id' => $officeId,
                'action' => $action,
                'entity_type' => 'App\User',
                'entity_id' => $effectiveUserId,
                'description' => SensitiveDataRedactor::redactString($description),
                'changes' => $sanitizedChanges,
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'user_agent' => substr((string)Request::userAgent(), 0, 255),
                'request_url' => substr((string)Request::fullUrl(), 0, 1000),
                'request_method' => Request::method() ?? 'CLI',
                'severity' => $severity,
                'is_suspicious' => $isSuspicious,
                'created_at' => now(),
            ]);

            $fileChannel = $severity === 'security' || $isSuspicious ? 'security' : 'audit';
            Log::channel($fileChannel)->info($description, [
                'activity_id' => $log->id,
                'action' => $action,
                'user_id' => $effectiveUserId,
                'ip' => Request::ip(),
            ]);

            return $log;
        } catch (Throwable $e) {
            Log::error('Failed to write auth activity log: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Record a security incident alert.
     */
    public static function createAlert(
        string $alertType,
        string $severity,
        string $title,
        string $description,
        ?array $metadata = null,
        ?int $userId = null,
        ?string $ipAddress = null
    ): ?SecurityAlert {
        try {
            $sanitizedMeta = $metadata ? SensitiveDataRedactor::redact($metadata) : null;
            $ip = $ipAddress ?? Request::ip() ?? '127.0.0.1';

            $alert = SecurityAlert::create([
                'alert_type' => $alertType,
                'severity' => $severity,
                'title' => SensitiveDataRedactor::redactString($title),
                'description' => SensitiveDataRedactor::redactString($description),
                'ip_address' => $ip,
                'user_id' => $userId ?? Auth::id(),
                'metadata' => $sanitizedMeta,
                'status' => 'open',
            ]);

            Log::channel('security')->warning('[SECURITY ALERT] ' . $title, [
                'alert_id' => $alert->id,
                'type' => $alertType,
                'severity' => $severity,
                'ip' => $ip,
                'user_id' => $userId ?? Auth::id(),
            ]);

            return $alert;
        } catch (Throwable $e) {
            Log::error('Failed to create security alert: ' . $e->getMessage());
            return null;
        }
    }
}
