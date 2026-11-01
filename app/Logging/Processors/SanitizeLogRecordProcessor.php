<?php

namespace App\Logging\Processors;

use App\Services\Security\SensitiveDataRedactor;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class SanitizeLogRecordProcessor implements ProcessorInterface
{
    /**
     * Process and redact sensitive data from the log record.
     *
     * @param LogRecord|array $record
     * @return LogRecord|array
     */
    public function __invoke(LogRecord|array $record): LogRecord|array
    {
        if ($record instanceof LogRecord) {
            $sanitizedMessage = SensitiveDataRedactor::redactString($record->message);
            $sanitizedContext = SensitiveDataRedactor::redact($record->context);
            $sanitizedExtra = SensitiveDataRedactor::redact($record->extra);

            return $record->with(
                message: $sanitizedMessage,
                context: is_array($sanitizedContext) ? $sanitizedContext : [],
                extra: is_array($sanitizedExtra) ? $sanitizedExtra : []
            );
        }

        if (is_array($record)) {
            if (isset($record['message']) && is_string($record['message'])) {
                $record['message'] = SensitiveDataRedactor::redactString($record['message']);
            }
            if (isset($record['context'])) {
                $record['context'] = SensitiveDataRedactor::redact($record['context']);
            }
            if (isset($record['extra'])) {
                $record['extra'] = SensitiveDataRedactor::redact($record['extra']);
            }
        }

        return $record;
    }
}
