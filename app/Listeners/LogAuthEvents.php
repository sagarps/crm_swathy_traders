<?php

namespace App\Listeners;

use App\Services\Security\ActivityLogger;
use App\Services\Security\AnomalyDetectionService;
use App\Services\Security\SensitiveDataRedactor;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Request;

class LogAuthEvents
{
    /**
     * Handle user login.
     */
    public function handleLogin(Login $event): void
    {
        $user = $event->user;
        $username = $user->username ?? $user->email ?? ('ID:' . $user->id);
        $ip = Request::ip() ?? '127.0.0.1';

        ActivityLogger::logAuth(
            action: 'login',
            description: "User {$username} logged in successfully",
            userId: $user->id,
            username: $username,
            severity: 'info',
            isSuspicious: false
        );

        AnomalyDetectionService::checkSuccessfulLogin($user->id, $ip);
    }

    /**
     * Handle failed login attempt.
     */
    public function handleFailed(Failed $event): void
    {
        $credentials = $event->credentials ?? [];
        // Strictly extract ONLY the non-secret identifier (username or email)
        $username = $credentials['username'] ?? $credentials['email'] ?? 'unknown';
        $ip = Request::ip() ?? '127.0.0.1';

        $isSuspicious = AnomalyDetectionService::checkFailedLogin($username, $ip);

        ActivityLogger::logAuth(
            action: 'login_failed',
            description: "Failed login attempt for username '{$username}' from IP {$ip}",
            userId: $event->user?->id,
            username: $username,
            severity: 'warning',
            isSuspicious: $isSuspicious
        );
    }

    /**
     * Handle user logout.
     */
    public function handleLogout(Logout $event): void
    {
        $user = $event->user;
        $username = $user ? ($user->username ?? $user->email ?? ('ID:' . $user->id)) : 'Unknown';

        ActivityLogger::logAuth(
            action: 'logout',
            description: "User {$username} logged out",
            userId: $user?->id,
            username: $username,
            severity: 'info',
            isSuspicious: false
        );
    }

    /**
     * Handle throttle lockout.
     */
    public function handleLockout(Lockout $event): void
    {
        $ip = Request::ip() ?? '127.0.0.1';
        $username = Request::input('username') ?? Request::input('email') ?? 'unknown';

        ActivityLogger::createAlert(
            alertType: 'account_lockout',
            severity: 'high',
            title: "Account Lockout Triggered for '{$username}'",
            description: "IP {$ip} was locked out due to excessive failed authentication requests.",
            metadata: [
                'target_username' => $username,
                'ip' => $ip,
            ],
            ipAddress: $ip
        );

        ActivityLogger::logAuth(
            action: 'lockout',
            description: "Account lockout triggered for username '{$username}' from IP {$ip}",
            userId: null,
            username: $username,
            severity: 'security',
            isSuspicious: true
        );
    }

    /**
     * Handle password reset.
     */
    public function handlePasswordReset(PasswordReset $event): void
    {
        $user = $event->user;
        $username = $user->username ?? $user->email ?? ('ID:' . $user->id);

        ActivityLogger::logAuth(
            action: 'password_reset',
            description: "Password reset completed for user {$username}",
            userId: $user->id,
            username: $username,
            severity: 'notice',
            isSuspicious: false
        );
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events): array
    {
        return [
            Login::class => 'handleLogin',
            Failed::class => 'handleFailed',
            Logout::class => 'handleLogout',
            Lockout::class => 'handleLockout',
            PasswordReset::class => 'handlePasswordReset',
        ];
    }
}
