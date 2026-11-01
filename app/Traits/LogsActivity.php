<?php

namespace App\Traits;

use App\Services\Security\ActivityLogger;
use App\Services\Security\SensitiveDataRedactor;

trait LogsActivity
{
    /**
     * Boot the LogsActivity trait for the model.
     */
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $changes = [
                'attributes' => $model->getSanitizedAttributesForLog($model->getAttributes()),
            ];
            $modelName = class_basename($model);
            ActivityLogger::log(
                action: 'created',
                description: "Created {$modelName} #{$model->getKey()}",
                entity: $model,
                changes: $changes,
                severity: 'info'
            );
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            if (empty($dirty)) {
                return;
            }

            $before = [];
            $after = [];

            foreach ($dirty as $key => $newValue) {
                if ($model->shouldIgnoreAttributeForLog($key)) {
                    continue;
                }

                $originalValue = $model->getOriginal($key);
                $before[$key] = $originalValue;
                $after[$key] = $newValue;
            }

            if (empty($after)) {
                return;
            }

            $changes = [
                'before' => SensitiveDataRedactor::redact($before),
                'after' => SensitiveDataRedactor::redact($after),
            ];

            $modelName = class_basename($model);
            ActivityLogger::log(
                action: 'updated',
                description: "Updated {$modelName} #{$model->getKey()} (" . implode(', ', array_keys($after)) . ')',
                entity: $model,
                changes: $changes,
                severity: 'info'
            );
        });

        static::deleted(function ($model) {
            $modelName = class_basename($model);
            $changes = [
                'attributes' => $model->getSanitizedAttributesForLog($model->getAttributes()),
            ];

            ActivityLogger::log(
                action: 'deleted',
                description: "Deleted {$modelName} #{$model->getKey()}",
                entity: $model,
                changes: $changes,
                severity: 'warning'
            );
        });
    }

    /**
     * Check if a model attribute should be excluded from activity logs.
     */
    public function shouldIgnoreAttributeForLog(string $attribute): bool
    {
        $ignored = array_merge(
            $this->getHidden(),
            ['updated_at', 'created_at', 'deleted_at'],
            property_exists($this, 'dontLogAttributes') ? (array)$this->dontLogAttributes : []
        );

        if (in_array($attribute, $ignored, true)) {
            return true;
        }

        return SensitiveDataRedactor::isSensitiveKey($attribute);
    }

    /**
     * Clean and redact attributes array before logging.
     */
    public function getSanitizedAttributesForLog(array $attributes): array
    {
        $clean = [];
        foreach ($attributes as $key => $value) {
            if ($this->shouldIgnoreAttributeForLog($key)) {
                continue;
            }
            $clean[$key] = $value;
        }

        return SensitiveDataRedactor::redact($clean);
    }
}
