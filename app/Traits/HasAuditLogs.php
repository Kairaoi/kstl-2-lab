<?php

namespace App\Traits;

use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Database\Eloquent\Model;

trait HasAuditLogs
{
    protected static function bootHasAuditLogs(): void
    {
        static::created(function (Model $model) {
            app(AuditService::class)->log(
                event:       AuditLog::EVENT_CREATED,
                description: class_basename($model) . " #{$model->id} created",
                auditable:   $model,
                newValues:   $model->getAttributes()
            );
        });

        static::updated(function (Model $model) {
            $changes = $model->getChanges();
            if (empty($changes)) {
                return;
            }

            app(AuditService::class)->log(
                event:       AuditLog::EVENT_UPDATED,
                description: class_basename($model) . " #{$model->id} updated",
                auditable:   $model,
                oldValues:   array_intersect_key($model->getOriginal(), $changes),
                newValues:   $changes
            );
        });

        static::deleted(function (Model $model) {
            app(AuditService::class)->log(
                event:       AuditLog::EVENT_DELETED,
                description: class_basename($model) . " #{$model->id} deleted",
                auditable:   $model,
                oldValues:   $model->getAttributes()
            );
        });

        // For SoftDeletes
        if (method_exists(static::class, 'restored')) {
            static::restored(function (Model $model) {
                app(AuditService::class)->log(
                    event:       AuditLog::EVENT_RESTORED,
                    description: class_basename($model) . " #{$model->id} restored",
                    auditable:   $model,
                    newValues:   $model->getAttributes()
                );
            });
        }
    }
}