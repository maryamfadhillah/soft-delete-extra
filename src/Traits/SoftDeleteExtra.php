<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SoftDeleteExtra
{
    /**
     * Boot the trait.
     */
    public static function bootSoftDeleteExtra()
    {
        static::addGlobalScope('is_deleted', function (Builder $builder) {
            $builder->where($builder->getModel()->getQualifiedIsDeletedColumn(), false);
        });
    }

    /**
     * Get the fully qualified "is_deleted" column.
     */
    public function getQualifiedIsDeletedColumn()
    {
        return $this->getTable().'.'.$this->getIsDeletedColumn();
    }

    /**
     * Get the "is_deleted" column name.
     */
    public function getIsDeletedColumn()
    {
        return defined('static::IS_DELETED') ? static::IS_DELETED : 'is_deleted';
    }

    /**
     * Perform the delete operation (soft delete).
     */
    protected function performDeleteOnModel()
    {
        if ($this->exists) {
            $this->{$this->getIsDeletedColumn()} = true;
            $this->deleted_by = auth()->id() ?: 'SYSTEM';
            $this->save();
        }
    }

    /**
     * Restore a soft-deleted model instance.
     */
    public function restore()
    {
        $this->{$this->getIsDeletedColumn()} = false;
        $this->deleted_by = NULL;
        $this->save();

        return $this;
    }

    /**
     * Force delete (hard delete).
     */
    public function forceDelete()
    {
        return parent::delete();
    }

    /**
     * Include soft-deleted records in query.
     */
    public static function withTrashed()
    {
        return (new static)->newQueryWithoutScope('is_deleted');
    }

    /**
     * Only soft-deleted records.
     */
    public static function onlyTrashed()
    {
        return (new static)->withTrashed()->where((new static)->getIsDeletedColumn(), true);
    }
}
