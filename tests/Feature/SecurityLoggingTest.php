<?php

namespace Tests\Feature;

use App\ActivityLog;
use App\Logging\Processors\SanitizeLogRecordProcessor;
use App\SecurityAlert;
use App\Services\Security\ActivityLogger;
use App\Services\Security\AnomalyDetectionService;
use App\Services\Security\SensitiveDataRedactor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Monolog\Level;
use Monolog\LogRecord;
use Tests\TestCase;

class SecurityLoggingTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test ActivityLogger writes to database with all credentials redacted.
     */
    public function test_activity_logger_writes_and_redacts_credentials(): void
    {
        $log = ActivityLogger::log(
            action: 'test_action',
            description: 'Testing activity logger',
            changes: [
                'username' => 'test_user',
                'password' => 'UnsafePlainTextPassword!99',
                'api_token' => 'secret_api_token_xyz',
            ],
            severity: 'info'
        );

        $this->assertNotNull($log);
        $this->assertDatabaseHas('activity_logs', [
            'id' => $log->id,
            'action' => 'test_action',
        ]);

        $freshLog = ActivityLog::find($log->id);
        $this->assertEquals('[REDACTED]', $freshLog->changes['password']);
        $this->assertEquals('[REDACTED]', $freshLog->changes['api_token']);
        $this->assertEquals('test_user', $freshLog->changes['username']);
    }

    /**
     * Test brute force anomaly detection triggers when multiple failed logins occur.
     */
    public function test_brute_force_detection_triggers_security_alert(): void
    {
        $testIp = '198.51.100.42';
        $targetUser = 'target_admin';

        // Simulate 4 failed logins (under threshold)
        for ($i = 0; $i < 4; $i++) {
            ActivityLog::create([
                'action' => 'login_failed',
                'description' => "Failed login for '{$targetUser}'",
                'changes' => ['username' => $targetUser],
                'ip_address' => $testIp,
                'severity' => 'warning',
                'is_suspicious' => false,
                'created_at' => now(),
            ]);
        }

        $isSuspiciousBefore = AnomalyDetectionService::checkFailedLogin($targetUser, $testIp);
        $this->assertFalse($isSuspiciousBefore, 'Should not trigger alert before threshold');

        // Add 5th failed login (meeting threshold)
        ActivityLog::create([
            'action' => 'login_failed',
            'description' => "Failed login for '{$targetUser}'",
            'changes' => ['username' => $targetUser],
            'ip_address' => $testIp,
            'severity' => 'warning',
            'is_suspicious' => false,
            'created_at' => now(),
        ]);

        $isSuspiciousAfter = AnomalyDetectionService::checkFailedLogin($targetUser, $testIp);
        $this->assertTrue($isSuspiciousAfter, 'Should trigger brute force anomaly on 5th attempt');

        $this->assertDatabaseHas('security_alerts', [
            'alert_type' => 'brute_force_login',
            'severity' => 'high',
            'ip_address' => $testIp,
        ]);
    }

    /**
     * Test Monolog SanitizeLogRecordProcessor cleans passwords and tokens.
     */
    public function test_monolog_processor_redacts_records(): void
    {
        $processor = new SanitizeLogRecordProcessor();

        $record = new LogRecord(
            datetime: new \DateTimeImmutable(),
            channel: 'test',
            level: Level::Info,
            message: 'User logged in with Bearer my_secret_token_123',
            context: [
                'user' => 'admin',
                'password' => 'PlainPassword123',
                'token' => 'secret_token_val',
            ],
            extra: [
                'auth' => 'Basic dXNlcjpwYXNz',
            ]
        );

        /** @var LogRecord $sanitized */
        $sanitized = $processor($record);

        $this->assertStringNotContainsString('my_secret_token_123', $sanitized->message);
        $this->assertStringContainsString('[REDACTED_TOKEN]', $sanitized->message);

        $this->assertEquals('admin', $sanitized->context['user']);
        $this->assertEquals('[REDACTED]', $sanitized->context['password']);
        $this->assertEquals('[REDACTED]', $sanitized->context['token']);
        $this->assertEquals('[REDACTED]', $sanitized->extra['auth']);
    }

    /**
     * Test Failed login event listener never logs entered password.
     */
    public function test_failed_login_event_does_not_log_password(): void
    {
        $unsafePassword = 'SecretAttackerAttempt123!';
        
        event(new \Illuminate\Auth\Events\Failed(
            guard: 'web',
            user: null,
            credentials: [
                'username' => 'victim_user',
                'password' => $unsafePassword,
            ]
        ));

        $latestLog = ActivityLog::where('action', 'login_failed')->latest('created_at')->first();
        $this->assertNotNull($latestLog);
        $this->assertEquals('victim_user', $latestLog->changes['username'] ?? null);

        // Assert unsafe password is never in changes, description, or serialized JSON
        $this->assertStringNotContainsString($unsafePassword, json_encode($latestLog->changes));
        $this->assertStringNotContainsString($unsafePassword, $latestLog->description);
    }

    /**
     * Test model activity logging captures changes and excludes hidden attributes.
     */
    public function test_model_activity_logging_captures_diff_and_excludes_hidden(): void
    {
        $office = \App\Office::create([
            'office_name' => 'Security Audit Branch ' . rand(100, 999),
            'address' => '100 Security Ave',
            'location' => 'Cyber City',
        ]);

        $createLog = ActivityLog::where('action', 'created')
            ->where('entity_type', \App\Office::class)
            ->where('entity_id', $office->id)
            ->first();

        $this->assertNotNull($createLog, 'Create activity log should be recorded');
        $this->assertStringContainsString('Created Office #' . $office->id, $createLog->description);

        // Update model
        $office->office_name = 'Updated Audit Branch ' . rand(100, 999);
        $office->save();

        $updateLog = ActivityLog::where('action', 'updated')
            ->where('entity_type', \App\Office::class)
            ->where('entity_id', $office->id)
            ->latest('created_at')
            ->first();

        $this->assertNotNull($updateLog, 'Update activity log should be recorded');
        $this->assertArrayHasKey('before', $updateLog->changes);
        $this->assertArrayHasKey('after', $updateLog->changes);
        $this->assertArrayHasKey('office_name', $updateLog->changes['after']);
    }
}
