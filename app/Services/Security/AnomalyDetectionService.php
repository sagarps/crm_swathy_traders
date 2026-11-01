<?php

namespace App\Services\Security;

use App\ActivityLog;
use App\SecurityAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Throwable;

class AnomalyDetectionService
{
    /**
     * Inspect failed login attempt for brute force or credential stuffing.
     */
    public static function checkFailedLogin(?string $username, string $ip): bool
    {
        try {
            $windowMinutes = 10;
            $threshold = 5;
            $cutoff = Carbon::now()->subMinutes($windowMinutes);

            // Failures from this IP
            $ipFailures = ActivityLog::where('action', 'login_failed')
                ->where('ip_address', $ip)
                ->where('created_at', '>=', $cutoff)
                ->count();

            // Failures targeting this username
            $userFailures = 0;
            if ($username) {
                $userFailures = ActivityLog::where('action', 'login_failed')
                    ->where('created_at', '>=', $cutoff)
                    ->where('changes->username', $username)
                    ->count();
            }

            if ($ipFailures >= $threshold || $userFailures >= $threshold) {
                // Avoid alert spam: don't create duplicate open alert for same IP within 15 minutes
                $recentAlert = SecurityAlert::where('alert_type', 'brute_force_login')
                    ->where('ip_address', $ip)
                    ->where('created_at', '>=', Carbon::now()->subMinutes(15))
                    ->first();

                if (!$recentAlert) {
                    ActivityLogger::createAlert(
                        alertType: 'brute_force_login',
                        severity: 'high',
                        title: "Potential Brute Force Attack from IP {$ip}",
                        description: "Detected {$ipFailures} failed login attempts from IP {$ip}" .
                            ($username ? " and {$userFailures} attempts targeting username '{$username}'" : "") .
                            " within {$windowMinutes} minutes.",
                        metadata: [
                            'ip_failures' => $ipFailures,
                            'user_failures' => $userFailures,
                            'target_username' => $username,
                            'window_minutes' => $windowMinutes,
                        ],
                        ipAddress: $ip
                    );
                }

                return true;
            }

            return false;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Inspect successful login for multi-IP session anomalies.
     */
    public static function checkSuccessfulLogin(int $userId, string $currentIp): void
    {
        try {
            $cutoff = Carbon::now()->subMinutes(20);

            // Find any other IP used by this user within the last 20 minutes
            $previousLogin = ActivityLog::where('action', 'login')
                ->where('user_id', $userId)
                ->where('created_at', '>=', $cutoff)
                ->where('ip_address', '!=', $currentIp)
                ->latest('created_at')
                ->first();

            if ($previousLogin) {
                ActivityLogger::createAlert(
                    alertType: 'multi_ip_session',
                    severity: 'medium',
                    title: "User Authenticated from Multiple IPs",
                    description: "User #{$userId} logged in from {$currentIp} within 20 minutes of active session from {$previousLogin->ip_address}.",
                    metadata: [
                        'user_id' => $userId,
                        'current_ip' => $currentIp,
                        'previous_ip' => $previousLogin->ip_address,
                        'previous_login_at' => $previousLogin->created_at?->toIso8601String(),
                    ],
                    userId: $userId,
                    ipAddress: $currentIp
                );
            }
        } catch (Throwable) {
            // Silently continue
        }
    }

    /**
     * Inspect access denied / forbidden spikes.
     */
    public static function checkAccessDenied(string $ip, ?int $userId, string $url): bool
    {
        try {
            $cutoff = Carbon::now()->subMinutes(5);
            $threshold = 5;

            $deniedCount = ActivityLog::where('action', 'access_denied')
                ->where('ip_address', $ip)
                ->where('created_at', '>=', $cutoff)
                ->count();

            if ($deniedCount >= $threshold) {
                $recentAlert = SecurityAlert::where('alert_type', 'unauthorized_spike')
                    ->where('ip_address', $ip)
                    ->where('created_at', '>=', Carbon::now()->subMinutes(15))
                    ->first();

                if (!$recentAlert) {
                    ActivityLogger::createAlert(
                        alertType: 'unauthorized_spike',
                        severity: 'medium',
                        title: "High Rate of Unauthorized (403/401) Requests from IP {$ip}",
                        description: "Detected {$deniedCount} forbidden/unauthorized attempts from IP {$ip} within 5 minutes. Last attempt targeted: {$url}",
                        metadata: [
                            'denied_count' => $deniedCount,
                            'last_url' => $url,
                            'user_id' => $userId,
                        ],
                        userId: $userId,
                        ipAddress: $ip
                    );
                }

                return true;
            }

            return false;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Check if an action occurred during unusual off-hours (11 PM - 5 AM).
     */
    public static function isOffHours(): bool
    {
        $hour = (int)date('G');
        return ($hour >= 23 || $hour < 5);
    }

    /**
     * Evaluate off-hours administrative activity.
     */
    public static function checkOffHoursAction(string $action, ?int $userId, string $description): void
    {
        if (self::isOffHours()) {
            ActivityLogger::createAlert(
                alertType: 'off_hours_access',
                severity: 'low',
                title: "Off-Hours Activity Detected",
                description: "Action '{$action}' performed during off-hours (between 11 PM and 5 AM). Details: {$description}",
                metadata: [
                    'action' => $action,
                    'user_id' => $userId,
                    'hour' => (int)date('G'),
                ],
                userId: $userId
            );
        }
    }

    /**
     * Track and flag rapid-fire requests from an IP address.
     */
    public static function checkRapidFire(string $ip): bool
    {
        try {
            $cacheKey = "req_rate_" . md5($ip);
            $count = Cache::increment($cacheKey);

            if ($count === 1) {
                Cache::put($cacheKey, 1, 60); // 1 minute window
            }

            if ($count >= 120) {
                $alertKey = "alert_rapid_" . md5($ip);
                if (!Cache::has($alertKey)) {
                    Cache::put($alertKey, true, 900); // 15 min alert throttle

                    ActivityLogger::createAlert(
                        alertType: 'rapid_scraping',
                        severity: 'high',
                        title: "Excessive Request Rate Detected from IP {$ip}",
                        description: "IP {$ip} made {$count} requests in 60 seconds, exceeding rate threshold.",
                        metadata: [
                            'request_count' => $count,
                            'window_seconds' => 60,
                        ],
                        ipAddress: $ip
                    );
                }
                return true;
            }

            return false;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Comprehensive scan across recent hours to identify anomalies and return statistics.
     */
    public static function scanRecentActivity(int $hours = 24): array
    {
        $cutoff = Carbon::now()->subHours($hours);

        $totalEvents = ActivityLog::where('created_at', '>=', $cutoff)->count();
        $failedLogins = ActivityLog::where('action', 'login_failed')
            ->where('created_at', '>=', $cutoff)
            ->count();
        $successfulLogins = ActivityLog::where('action', 'login')
            ->where('created_at', '>=', $cutoff)
            ->count();
        $suspiciousEvents = ActivityLog::where('is_suspicious', true)
            ->where('created_at', '>=', $cutoff)
            ->count();
        $openAlerts = SecurityAlert::open()->count();

        // Top IPs with failed logins
        $topFailedIps = ActivityLog::where('action', 'login_failed')
            ->where('created_at', '>=', $cutoff)
            ->selectRaw('ip_address, COUNT(*) as count')
            ->groupBy('ip_address')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('count', 'ip_address')
            ->toArray();

        return [
            'period_hours' => $hours,
            'total_events' => $totalEvents,
            'successful_logins' => $successfulLogins,
            'failed_logins' => $failedLogins,
            'suspicious_events' => $suspiciousEvents,
            'open_alerts' => $openAlerts,
            'top_failed_ips' => $topFailedIps,
        ];
    }
}
