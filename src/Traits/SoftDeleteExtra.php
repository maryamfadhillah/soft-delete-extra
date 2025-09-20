<?php

namespace SoftDeleteExtra\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait SoftDeleteExtra
{
    use SoftDeletes;

    protected static function bootSoftDeleteExtra()
    {
        static::deleting(function ($model) {
            if (! $model->isForceDeleting()) {
                if ($model->isFillable('deleted_by')) {
                    $model->deleted_by = Auth::id() ?? 'SYSTEM';
                }
                if ($model->isFillable('is_deleted')) {
                    $model->is_deleted = 1;
                }
                $model->save();
            }
        });
    }
}
