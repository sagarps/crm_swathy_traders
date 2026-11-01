<?php

namespace Tests\Unit;

use App\Services\Security\SensitiveDataRedactor;
use PHPUnit\Framework\TestCase;

class SensitiveDataRedactorTest extends TestCase
{
    /**
     * Test direct sensitive keys are redacted.
     */
    public function test_sensitive_keys_are_redacted(): void
    {
        $input = [
            'username' => 'john_doe',
            'password' => 'SuperSecret123!',
            'password_confirmation' => 'SuperSecret123!',
            'token' => 'abc123xyztoken',
            'api_token' => 'sec_token_999',
            'secret_key' => 'very_secret_key',
            'remember_token' => 'rem_tok_555',
            'status' => 'ACTIVE',
        ];

        $redacted = SensitiveDataRedactor::redact($input);

        $this->assertEquals('john_doe', $redacted['username']);
        $this->assertEquals('ACTIVE', $redacted['status']);
        $this->assertEquals('[REDACTED]', $redacted['password']);
        $this->assertEquals('[REDACTED]', $redacted['password_confirmation']);
        $this->assertEquals('[REDACTED]', $redacted['token']);
        $this->assertEquals('[REDACTED]', $redacted['api_token']);
        $this->assertEquals('[REDACTED]', $redacted['secret_key']);
        $this->assertEquals('[REDACTED]', $redacted['remember_token']);
    }

    /**
     * Test nested structures with credentials are deeply redacted.
     */
    public function test_nested_arrays_are_recursively_redacted(): void
    {
        $input = [
            'request' => [
                'headers' => [
                    'authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9',
                    'content-type' => 'application/json',
                ],
                'payload' => [
                    'user' => [
                        'username' => 'admin',
                        'current_password' => 'old_pass_123',
                        'new_password' => 'new_pass_456',
                    ],
                ],
            ],
        ];

        $redacted = SensitiveDataRedactor::redact($input);

        $this->assertEquals('application/json', $redacted['request']['headers']['content-type']);
        $this->assertEquals('[REDACTED]', $redacted['request']['headers']['authorization']);
        $this->assertEquals('admin', $redacted['request']['payload']['user']['username']);
        $this->assertEquals('[REDACTED]', $redacted['request']['payload']['user']['current_password']);
        $this->assertEquals('[REDACTED]', $redacted['request']['payload']['user']['new_password']);
    }

    /**
     * Test inline strings containing bearer tokens or basic auth are masked.
     */
    public function test_string_token_patterns_are_masked(): void
    {
        $text = 'Calling API with Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9 and secret="my_super_secret"';
        $cleaned = SensitiveDataRedactor::redactString($text);

        $this->assertStringNotContainsString('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9', $cleaned);
        $this->assertStringNotContainsString('my_super_secret', $cleaned);
        $this->assertStringContainsString('[REDACTED_TOKEN]', $cleaned);
        $this->assertStringContainsString('[REDACTED]', $cleaned);
    }
}
