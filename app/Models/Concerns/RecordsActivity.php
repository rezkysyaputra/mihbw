<?php

namespace App\Models\Concerns;

use Spatie\Activitylog\LogOptions;

trait RecordsActivity
{
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->activityLogName())
            ->logOnly($this->activityLogAttributes())
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName): string => $this->activityLogDescription($eventName));
    }

    protected function activityLogName(): string
    {
        return class_basename($this);
    }

    protected function activityLogAttributes(): array
    {
        return $this->getFillable();
    }

    protected function activityLogDescription(string $eventName): string
    {
        $eventLabels = [
            'created' => 'dibuat',
            'updated' => 'diperbarui',
            'deleted' => 'dihapus',
        ];

        return $this->activityLogName() . ' ' . ($eventLabels[$eventName] ?? $eventName);
    }
}
