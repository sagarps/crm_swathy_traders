<?php

namespace App\Http\Middleware;

use App\Services\Security\ActivityLogger;
use App\Services\Security\AnomalyDetectionService;
use App\Services\Security\SensitiveDataRedactor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LogHttpActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $ip = $request->ip() ?? '127.0.0.1';

        // 1. Check for rapid rate burst / scraper anomaly
        AnomalyDetectionService::checkRapidFire($ip);

        /** @var Response $response */
        $response = $next($request);

        $durationMs = round((microtime(true) - $startTime) * 1000, 2);
        $statusCode = $response->getStatusCode();
        $user = Auth::user();
        $userId = $user?->id;

        // 2. Detect 401 Unauthorized / 403 Forbidden spikes
        if ($statusCode === 401 || $statusCode === 403) {
            $isSuspicious = AnomalyDetectionService::checkAccessDenied($ip, $userId, $request->fullUrl());

            ActivityLogger::log(
                action: 'access_denied',
                description: "HTTP {$statusCode} Access Denied for {$request->method()} {$request->path()} from IP {$ip}",
                entity: null,
                changes: [
                    'status_code' => $statusCode,
                    'duration_ms' => $durationMs,
                    'path' => $request->path(),
                ],
                severity: 'warning',
                isSuspicious: $isSuspicious
            );
        }

        // 3. Detect off-hours administrative changes (User, Role, Office updates)
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $path = $request->path();
            if (str_contains($path, 'user') || str_contains($path, 'role') || str_contains($path, 'office')) {
                AnomalyDetectionService::checkOffHoursAction(
                    action: "admin_request_{$request->method()}",
                    userId: $userId,
                    description: "Administrative request to {$path} from IP {$ip}"
                );
            }
        }

        return $response;
    }
}
